{{-- @php use App\Models\PackageAmenity;
 use App\Models\Amenity;
 use App\Models\Package; @endphp --}}
@extends('front.layout.master')
@section('main_content')

    <div class="page-top">
        <div class="container">
                    <div class="breadcrumb-container">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Start</a></li>
                            <li class="breadcrumb-item active">Wycieczki szkolne</li>
                        </ol>
                    </div>
                </div>
            </div>

    <div class="package pt_20 pb_50">
        <div class="region-information">
            <div class="text">
                Pokaż ofertę dla:
            <script>
                function autoSubmit() {
                    document.getElementById("regionForm").submit();
                }
            </script>
                {{-- <form name="regionForm" id="regionForm" action="{{ route('packages') }}" method="get"> --}}
                <form name="regionForm" id="regionForm" action="#" method="get">
                    <div class="select-form-div">
                        <form name="regionFormInner" id="regionFormInner" action="{{ route('packages') }}" method="get">
                            <select name="start_place_id" id="start_place_id_top" class="form-select-region-information" onchange="saveStartPlaceId(this.value); this.form.submit()">
                                @if(isset($startPlaces))
                                    @foreach($startPlaces as $place)
                                        <option value="{{ $place->id }}" @if($start_place_id == $place->id) selected @endif>{{ $place->name }} i okolice</option>
                                    @endforeach
                                @endif
                            </select>
                        </form>
                    </div>
                </form>

                <div class="icon"><i class="fas fa-info-circle"></i>
                <div class="explanation">
                    Prosimy o wybranie miasta opowiadającego miejscu wyjazdu lub miasta, które znajduje się najbliżej.
                </div>
            </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="sidebars col-lg-3 col-md-4 col-xs-1 mb-5" style="padding-right: 30px">
                    <div id="filter-show" class="mobile-filter-button">
                        <div class="show"><i class="fas fa-filter"></i> Pokaż filtry</div>
                    </div>

                    <!-- move this to another file -->
                    <script>
                        function toggleFilters() {
                            var div = document.getElementById('filters');
                            var filterShowDiv = document.getElementById('filter-show');

                            // Toggle visibility of filters
                            if (div.style.display !== 'block') {
                                div.style.display = 'block';
                                filterShowDiv.innerHTML = '<div class="show"><i class="fas fa-filter"></i> Ukryj filtry</div>';
                            } else {
                                div.style.display = 'none';
                                filterShowDiv.innerHTML = '<div class="show"><i class="fas fa-filter"></i> Pokaż filtry</div>';
                            }
                        }

                        function updateFiltersDisplay() {
                            var div = document.getElementById('filters');
                            const viewportWidth = window.innerWidth;
                            var filterShowDiv = document.getElementById('filter-show');

                            if (viewportWidth < 798) {
                                // Ensure that the toggle button works only when the screen is small
                                if (!filterShowDiv.onclick) {
                                    filterShowDiv.onclick = toggleFilters; // Assign onclick if not already assigned
                                }

                                // Hide the filters when screen is small
                                if (div.style.display !== 'block') {
                                    div.style.display = 'none';
                                }
                            } else {
                                // Show the filters when the screen is large enough
                                div.style.display = 'block';

                                // Remove onclick listener when the screen is large
                                if (filterShowDiv.onclick) {
                                    filterShowDiv.onclick = null;
                                }
                            }
                        }

                        // Call once on page load to initialize everything
                        window.onload = function() {
                            updateFiltersDisplay();
                        }

                        // Attach resize event to handle window resizing
                        window.addEventListener('resize', function() {
                            updateFiltersDisplay();  // Call update on resize
                            // Re-attach the toggleFilters in case it's missing after resizing
                            if (window.innerWidth < 798 && !document.getElementById('filter-show').onclick) {
                                document.getElementById('filter-show').onclick = toggleFilters;
                            }
                        });

                    </script>

                    <div id="filters" class="package-sidebar">
                        {{-- <form action="{{ route('packages') }}" method="get"> --}}
                        <form action="#" method="get">
                            <div class="widget">
                                <h2>Pokaż ofertę dla</h2>
                                <div class="box">
                                    <select name="start_place_id" id="start_place_id_sidebar" class="form-select" onchange="saveStartPlaceId(this.value); this.form.submit()">
                                        @if(isset($startPlaces))
                                            @foreach($startPlaces as $place)
                                                <option value="{{ $place->id }}" @if($start_place_id == $place->id) selected @endif>{{ $place->name }} i okolice</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
<script>
// Helper: set cookie
function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}

// Helper: get cookie
function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

// Save start_place_id to cookie
function saveStartPlaceId(val) {
    setCookie('start_place_id', val, 30);
}

// On page load: save current start_place_id to cookie only if backend used default Warszawa
document.addEventListener('DOMContentLoaded', function() {
    @if($usedDefaultWarszawa)
        // Backend used default Warszawa because there was no cookie or URL parameter
        setCookie('start_place_id', '{{ $start_place_id }}', 30);
    @endif
});
</script>
                            <div class="widget">
                                <h2>Sortuj według</h2>
                                <div class="box">
                                    <select name="sort_by" class="form-select">
                                        <option value="">Domyślne sortowanie</option>
                                        <option value="price_asc" @if(request('sort_by') == 'price_asc') selected @endif>Cena: od najniższej</option>
                                        <option value="price_desc" @if(request('sort_by') == 'price_desc') selected @endif>Cena: od najwyższej</option>
                                        <option value="name_asc" @if(request('sort_by') == 'name_asc') selected @endif>Alfabetycznie A-Z</option>
                                        <option value="name_desc" @if(request('sort_by') == 'name_desc') selected @endif>Alfabetycznie Z-A</option>
                                    </select>
                                </div>
                            </div>
                            <div class="widget">
                                <h2>Długość wycieczki</h2>
                                <div class="box">
                                    <select name="length_id" class="form-select">
                                        <option value="">Wszystkie długości</option>
                                        <option value="1" @if(request('length_id') == '1') selected @endif>1 dzień</option>
                                        <option value="2" @if(request('length_id') == '2') selected @endif>2 dni</option>
                                        <option value="3" @if(request('length_id') == '3') selected @endif>3 dni</option>
                                        <option value="4" @if(request('length_id') == '4') selected @endif>4 dni</option>
                                        <option value="5" @if(request('length_id') == '5') selected @endif>5 dni</option>
                                        <option value="6plus" @if(request('length_id') == '6plus') selected @endif>6 dni i więcej</option>
                                    </select>
                                </div>
                            </div>
                        <div class="widget">
                            <h2>Cena</h2>
                            <div class="box">
                                <div class="row">
                                    <div class="col-md-6">
                                        {{-- <input type="text" name="min_price" class="form-control" placeholder="Min" value="{{ $form_min_price }}"> --}}
                                        <input type="text" name="min_price" class="form-control" placeholder="Min" value="">
                                    </div>
                                    <div class="col-md-6">
                                        {{-- <input type="text" name="max_price" class="form-control" placeholder="Max" value="{{ $form_max_price }}"> --}}
                                        <input type="text" name="max_price" class="form-control" placeholder="Max" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
<div class="widget">
    <h2>Kierunek</h2>
    <div class="box">
        <input type="text" name="destination_name" class="form-control" placeholder="Wpisz kierunek" value="{{ request('destination_name', '') }}">
    </div>
</div>
                            <div class="widget">
                                <h2>Typ wycieczki</h2>
                                <div class="box">
                                    <select name="event_type_id" class="form-select">
                                        <option value="">Wszystkie typy wycieczek</option>
                                        @if(isset($eventTypes))
                                            @foreach($eventTypes as $eventType)
                                                <option value="{{ $eventType->id }}" @if(request('event_type_id') == $eventType->id) selected @endif>{{ $eventType->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="widget">
                                <h2>Środek transportu</h2>
                                <div class="box">
                                    <select name="transport_id" class="form-select">
                                        <option value="">Wszystkie środki transportu</option>
                                        {{-- @foreach($transports as $transport)
                                            <option value="{{ $transport->id }}" @if($form_transport_id == $transport->id) selected @endif>{{ $transport->name }}</option>
                                        @endforeach --}}
                                        <option value="1">Autokar</option>
                                        <option value="2">Autobus</option>
                                        <option value="3">Pociąg</option>
                                        <option value="4">Samolot</option>
                                        <option value="5">Własny transport</option>
                                    </select>
                                </div>
                            </div>
                        <div class="filter-button">
                            <button type="submit" class="btn btn-primary">Filtruj</button>
                        </div>
                    </form>
                    </div>
                </div>


                 <div class="col-lg-9 col-md-8 col-xs-1">
                    @foreach($eventTemplate as $item)
                        <div class="item pb_25">
                <div class="package-box">
                    <div class="package-box-layout">
                        <div
                            class="package-box-photo"
                            style="background-image: url({{ asset('storage/' . ($item->featured_image ?? '')) }}); cursor: pointer;"
                            onclick="window.location.href='{{ route('package', $item->slug) }}';">
                        </div>
                        <div class="package-box-name-mobile">
                            <a href="{{ route('package',$item->slug) }}">{{ $item->name }}</a></div>
                        <div class="package-box-info">
                            <div class="left">
                                <div class="package-box-name">
                                    <a href="{{ route('package',$item->slug) }}">{{ $item->name }}</a>
                                    @if($item->subtitle)
                                        <div class="package-box-subtitle">{{ $item->subtitle }}</div>
                                    @endif
                                </div>
                                <div class="package-box-small-info">
                                    {{-- <div class="package-box-region">
                                        {{ $item->region->name }} <i class="fas fa-arrow-right"></i> {{ $item->destination->name }}
                                    </div> --}}
                                    <div class="package-box-time">
                                        <i class="fas fa-clock"></i> {{ $item->duration_days }} dni
                                    </div>
                                    {{-- <div class="package-box-transportation">
                                        Środek transportu: {{$item->transport->name}}
                                    </div> --}}
                                </div>
                                <div class="package-box-positioning-graphic-info"></div>
                                <div class="package-box-graphic-info">
                                    <div class="amenity-title">Tagi:</div>

                                    <div class="package-box-tags">
                                        @if ($item->tags && $item->tags->isNotEmpty())
                                            @foreach ($item->tags as $tag)
                                                <div class="tag">{{ $tag->name }}</div>
                                            @endforeach
                                        @endif
                                    </div></div>
                                </div>
                            <div class="right">
                                <div class="price-2-boxes">
                                <div class="package-box-actual-price">
                                    @php
                                        $minPrice = null;
                                        if ($item->pricesPerPerson && $item->pricesPerPerson->count()) {
                                            $validPrices = $item->pricesPerPerson->where('price_per_person', '>', 0);
                                            if ($validPrices->count() > 0) {
                                                $minPrice = ceil($validPrices->min('price_per_person') / 5) * 5;
                                            }
                                        }
                                    @endphp
                                    od <b>{{ $minPrice ?? '—' }} zł</b> /os.
                                </div>
                            <div class="package-box-price">
                                <a href="{{ route('package',parameters: $item->slug) }}">Pokaż ofertę</a>
                            </div>
                                </div>
                            </div></div>
                        </div>
                    </div>
                </div>
                    @endforeach
                </div>


    </div>
@endsection
