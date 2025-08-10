<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\EventTemplate;
use App\Models\EventTemplateStartingPlaceAvailability;
use App\Models\Place;
use App\Models\EventType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cookie;

class FrontController extends Controller
{
    /**
     * Remove diacritics from a string (for fuzzy search)
     */
    private function removeDiacritics($string)
    {
        $diacritics = [
            'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ż' => 'z', 'ź' => 'z',
            'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'E', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'O', 'Ś' => 'S', 'Ż' => 'Z', 'Ź' => 'Z',
        ];
        return strtr($string, $diacritics);
    }
    public function blog(Request $request)
    {
        // Featured posts (max 3)
        $featuredPosts = BlogPost::where('status', 'active')
            ->where('published_at', '<=', now())
            ->where('is_featured', true)
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        // Fill with regular posts if not enough featured
        $remainingCount = 3 - $featuredPosts->count();
        $regularPosts = collect();
        if ($remainingCount > 0) {
            $excludeIds = $featuredPosts->pluck('id')->toArray();
            $regularPosts = BlogPost::where('status', 'active')
                ->where('published_at', '<=', now())
                ->whereNotIn('id', $excludeIds)
                ->orderBy('published_at', 'desc')
                ->take($remainingCount)
                ->get();
        }
        $blogPosts = $featuredPosts->merge($regularPosts);
        return view('front.blog', compact('blogPosts'));
    }

    public function blogPost($slug)
    {
        $blogPost = BlogPost::where('slug', $slug)
            ->where('status', 'active')
            ->where('published_at', '<=', now())
            ->firstOrFail();

        // Previous/next navigation
        $previousPost = BlogPost::where('status', 'active')
            ->where('published_at', '<=', now())
            ->where('published_at', '<', $blogPost->published_at)
            ->orderBy('published_at', 'desc')
            ->first();
        $nextPost = BlogPost::where('status', 'active')
            ->where('published_at', '<=', now())
            ->where('published_at', '>', $blogPost->published_at)
            ->orderBy('published_at', 'asc')
            ->first();

        return view('front.blog-post', compact('blogPost', 'previousPost', 'nextPost'));
    }
    public function directorypackages(Request $request)
    {
        $form_name = $request->name;
        $form_min_price = $request->min_price;
        $form_max_price = $request->max_price;
        $form_destination_id = $request->destination_id;
        $form_length_id = $request->length_id;

    $region_id = $request->region_id ?? Cookie::get('region_id', 16);

        if ($request->region_id) {
            Cookie::queue('region_id', $request->region_id, 60 * 24 * 365); // 1 year expiration
        }

        $mapEventTemplate = function($eventTemplate) {
            $eventTemplate->featured_photo = $eventTemplate->featured_image ?: 'default.png';
            $eventTemplate->description = $eventTemplate->event_description;
            $eventTemplate->length_id = $eventTemplate->duration_days;
            $eventTemplate->price = '0';
            $eventTemplate->old_price = null;
            $eventTemplate->transport_id = null;
            $eventTemplate->destination_id = null;
            $eventTemplate->region_id = null;
            $eventTemplate->length = (object) [
                'id' => $eventTemplate->duration_days,
                'name' => $eventTemplate->duration_days == 1 ? '1 dzień' : $eventTemplate->duration_days . ' dni'
            ];
            $eventTemplate->transport = (object) [
                'id' => null,
                'name' => 'Nie określono'
            ];
            return $eventTemplate;
        };

        $random_one_day = \App\Models\EventTemplate::where('duration_days', '=', 1)->take(8)->get()->map($mapEventTemplate);
        $random_one_day_mobile = \App\Models\EventTemplate::where('duration_days', '=', 1)->take(3)->get()->map($mapEventTemplate);
        $random_two_day = \App\Models\EventTemplate::where('duration_days', '=', 2)->take(8)->get()->map($mapEventTemplate);
        $random_three_day = \App\Models\EventTemplate::where('duration_days', '=', 3)->take(8)->get()->map($mapEventTemplate);
        $random_four_day = \App\Models\EventTemplate::where('duration_days', '=', 4)->take(8)->get()->map($mapEventTemplate);
        $random_five_day = \App\Models\EventTemplate::where('duration_days', '=', 5)->take(8)->get()->map($mapEventTemplate);
        $random_six_day = \App\Models\EventTemplate::where('duration_days', '>=', 6)->take(8)->get()->map($mapEventTemplate);

    $destinations = class_exists('App\\Models\\Destination') ? \App\Models\Destination::orderBy('name','asc')->get() : collect();
    $regions = class_exists('App\\Models\\Region') ? \App\Models\Region::orderBy('name','asc')->get() : collect();

        $lengths = \App\Models\EventTemplate::select('duration_days')
            ->whereNotNull('duration_days')
            ->distinct()
            ->orderBy('duration_days', 'asc')
            ->get()
            ->map(function($template) {
                return (object)[
                    'id' => $template->duration_days,
                    'name' => $template->duration_days == 1 ? '1 dzień' : $template->duration_days . ' dni'
                ];
            });

        $query = \App\Models\EventTemplate::orderBy('slug', 'desc');
        if ($form_name) {
            $query->where('name', 'like', '%' . $form_name . '%');
        }
        if ($form_min_price) {
            $query->where('price', '>', $form_min_price);
        }
        if ($form_max_price) {
            $query->where('price', '<', $form_max_price);
        }
        if ($form_destination_id) {
            $query->where('destination_id', $form_destination_id);
        }
        if ($form_length_id) {
            $query->where('length_id', $form_length_id);
        }
        if ($region_id) {
            $query->where('region_id', $region_id);
        }
        $packages = $query->paginate(12);
        $packages->getCollection()->transform($mapEventTemplate);

        return view('front.directory-packages', compact(
            'random_six_day', 'random_five_day', 'random_four_day', 'random_three_day',
            'random_two_day', 'random_one_day_mobile', 'random_one_day',
            'destinations', 'regions', 'lengths', 'packages',
            'form_name', 'form_min_price', 'form_max_price', 'form_destination_id',
            'region_id', 'form_length_id'
        ));
    }
    public function home(Request $request)
    {
        // Pobierz prawdziwe start_place_id z bazy - tak samo jak w packages()
        $startPlaceIds = EventTemplateStartingPlaceAvailability::query()
            ->select('start_place_id')
            ->distinct()
            ->pluck('start_place_id');
        $startPlaces = Place::whereIn('id', $startPlaceIds)->orderBy('name')->get();

        // Użyj tej samej logiki co w packages() dla spójności
        $start_place_id = request('start_place_id');
        if (!$start_place_id || $start_place_id === '') {
            $start_place_id = session('start_place_id');
            
            // Jeśli nie ma session lub session ma nieprawidłową wartość, zostaw puste (nie ustawiaj domyślnej Warszawy na home)
            if ($start_place_id && !$startPlaces->where('id', $start_place_id)->first()) {
                $start_place_id = null;
            }
        }

        $durations = [
            (object)['id' => 1, 'name' => '1 dzień'],
            (object)['id' => 2, 'name' => '2 dni'],
            (object)['id' => 3, 'name' => '3 dni'],
            (object)['id' => 5, 'name' => '5 dni'],
            (object)['id' => 7, 'name' => '7 dni'],
        ];

        // Fetch 12 active EventTemplates for carousel
        $random = \App\Models\EventTemplate::where('is_active', true)
            ->take(12)
            ->get()
            ->map(function($eventTemplate) {
                // Featured photo for asset('uploads/')
                $eventTemplate->featured_photo = $eventTemplate->featured_image ? basename($eventTemplate->featured_image) : 'default.png';
                // Length object for blade
                $eventTemplate->length = (object) [
                    'id' => $eventTemplate->duration_days,
                    'name' => $eventTemplate->duration_days == 1 ? '1 dzień' : $eventTemplate->duration_days . ' dni'
                ];
                // Price (stub: always "0" unless you have logic)
                $eventTemplate->price = "0";
                $eventTemplate->currency_symbol = 'PLN';
                return $eventTemplate;
            });
        $random_chunks = $random->chunk(4);

        $eventTypes = \App\Models\EventType::orderBy('name')->get();

        // Pobierz najnowsze aktywne posty blogowe (do 3)
        $blogPosts = \App\Models\BlogPost::where('status', 'active')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        return view('front.home', [
            'startPlaces' => $startPlaces,
            'start_place_id' => $start_place_id,
            'durations' => collect($durations),
            'random_chunks' => $random_chunks,
            'blogPosts' => $blogPosts,
            'eventTypes' => $eventTypes,
        ]);
    }

    public function packages()
    {
        // Pobierz prawdziwe dane z bazy
        $length_id = request('length_id');
        $sort_by = request('sort_by');
        $start_place_id = request('start_place_id');
        $event_type_id = request('event_type_id');

        // Pobierz unikalne start_place_id z event_template_starting_place_availability
        $startPlaceIds = EventTemplateStartingPlaceAvailability::query()
            ->select('start_place_id')
            ->distinct()
            ->pluck('start_place_id');
        $startPlaces = Place::whereIn('id', $startPlaceIds)->orderBy('name')->get();

        // Śledzenie czy użyto domyślnej wartości Warszawa
        $usedDefaultWarszawa = false;
        
        // Jeśli nie ma wybranego start_place_id w URL lub jest pusty, sprawdź cookie
        if (!$start_place_id || $start_place_id === '') {
            $start_place_id = request()->cookie('start_place_id');
            
            // Jeśli nie ma cookie lub cookie ma nieprawidłową wartość, ustaw Warszawa jako domyślną
            if (!$start_place_id || !$startPlaces->where('id', $start_place_id)->first()) {
                $warszawaPlace = $startPlaces->firstWhere('name', 'Warszawa');
                if ($warszawaPlace) {
                    $start_place_id = $warszawaPlace->id;
                    $usedDefaultWarszawa = true;
                }
            }
        }

        // Pobierz wszystkie Event Types dla filtra
        $eventTypes = EventType::orderBy('name')->get();

        $eventTemplate = EventTemplate::where('is_active', true)
            ->with(['tags', 'programPoints', 'startingPlaceAvailabilities.startPlace', 'eventTypes'])
            ->when($length_id, function($query) use ($length_id) {
                if ($length_id === '6plus') {
                    $query->where('duration_days', '>=', 6);
                } elseif ($length_id) {
                    $query->where('duration_days', $length_id);
                }
            })
            ->when($start_place_id, function($query) use ($start_place_id) {
                $query->whereHas('startingPlaceAvailabilities', function($q) use ($start_place_id) {
                    $q->where('start_place_id', $start_place_id);
                });
            })
            ->when($event_type_id, function($query) use ($event_type_id) {
                $query->whereHas('eventTypes', function($q) use ($event_type_id) {
                    $q->where('event_types.id', $event_type_id);
                });
            })
            ->get();

        // Fuzzy search for destination_name (by name or tags, diacritics-insensitive)
        $destination_name = request('destination_name');
        if ($destination_name) {
            $search = $this->removeDiacritics(mb_strtolower($destination_name));
            $eventTemplate = $eventTemplate->filter(function($item) use ($search) {
                $name = $this->removeDiacritics(mb_strtolower($item->name));
                $nameMatch = strpos($name, $search) !== false;
                $tagMatch = $item->tags && $item->tags->contains(function($tag) use ($search) {
                    $tagName = $this->removeDiacritics(mb_strtolower($tag->name));
                    return strpos($tagName, $search) !== false;
                });
                return $nameMatch || $tagMatch;
            })->values();
        }

        // Sortowanie kolekcji po cenie lub nazwie
        if ($sort_by === 'price_asc') {
            $eventTemplate = $eventTemplate->sort(function($a, $b) {
                $aPrice = is_numeric($a->price) ? (float)$a->price : null;
                $bPrice = is_numeric($b->price) ? (float)$b->price : null;
                if ($aPrice === null && $bPrice === null) return 0;
                if ($aPrice === null) return 1;
                if ($bPrice === null) return -1;
                return $aPrice <=> $bPrice;
            })->values();
        } elseif ($sort_by === 'price_desc') {
            $eventTemplate = $eventTemplate->sort(function($a, $b) {
                $aPrice = is_numeric($a->price) ? (float)$a->price : null;
                $bPrice = is_numeric($b->price) ? (float)$b->price : null;
                if ($aPrice === null && $bPrice === null) return 0;
                if ($aPrice === null) return 1;
                if ($bPrice === null) return -1;
                return $bPrice <=> $aPrice;
            })->values();
        } elseif ($sort_by === 'name_asc') {
            $eventTemplate = $eventTemplate->sortBy('name', SORT_NATURAL|SORT_FLAG_CASE)->values();
        } elseif ($sort_by === 'name_desc') {
            $eventTemplate = $eventTemplate->sortByDesc('name', SORT_NATURAL|SORT_FLAG_CASE)->values();
        }

        return view('front.packages', [
            'eventTemplate' => $eventTemplate,
            'startPlaces' => $startPlaces,
            'start_place_id' => $start_place_id,
            'eventTypes' => $eventTypes,
            'event_type_id' => $event_type_id,
            'usedDefaultWarszawa' => $usedDefaultWarszawa,
        ]);
    }

    public function package($slug)
    {
        // Pobierz konkretny EventTemplate na podstawie slug
        $eventTemplate = EventTemplate::where('slug', $slug)
            ->where('is_active', true)
            ->with(['tags', 'programPoints'])
            ->firstOrFail();

        return view('front.package', [
            'eventTemplate' => $eventTemplate,
            'item' => $eventTemplate, // alias dla kompatybilności z szablonem
        ]);
    }

    public function insurance()
    {
        return view('front.insurance');
    }

    public function documents()
    {
        return view('front.documents');
    }

    public function contact()
    {
        return view('front.contact');
    }

    public function sendEmail(Request $request)
    {
        // Walidacja (przykład)
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'telephone' => 'nullable',
            'message' => 'required',
        ]);

        // Log debug info to confirm controller is called
        Log::info('sendEmail controller called', $validated);

        // Wysyłka maila (do loga)
        Mail::raw(
            "Imię i nazwisko: {$validated['name']}\nEmail: {$validated['email']}\nTelefon: {$validated['telephone']}\nWiadomość: {$validated['message']}",
            function($mail) use ($validated) {
                $mail->to('test@example.com')
                    ->subject('Nowa wiadomość z formularza kontaktowego');
            }
        );

        return redirect()->back()->with('success', 'Wiadomość została wysłana!');
    }

}
