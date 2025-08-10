<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EventTemplate;
use App\Models\Place;
use App\Models\EventTemplateStartingPlaceAvailability;

class EventTemplateStartingPlaceSeeder extends Seeder
{
    /**
     * Przypisz każdemu EventTemplate miasto wojewódzkie jako starting place
     */
    public function run(): void
    {
        // Pobierz 10 największych polskich miast z bazy (jeśli są)
        $cityNames = [
            'Warszawa', 'Kraków', 'Łódź', 'Wrocław', 'Poznań', 'Gdańsk', 'Szczecin', 'Bydgoszcz', 'Lublin', 'Katowice'
        ];
        $places = Place::whereIn('name', $cityNames)->pluck('id', 'name');
        $placeIds = $places->values()->all();
        $templates = EventTemplate::all();
        foreach ($templates as $template) {
            // Każdy event_template dostaje losowe 2-5 miast z 10
            $randomPlaces = collect($placeIds)->shuffle()->take(rand(2,5));
            foreach ($randomPlaces as $placeId) {
                EventTemplateStartingPlaceAvailability::firstOrCreate([
                    'event_template_id' => $template->id,
                    'start_place_id' => $placeId,
                ], [
                    'end_place_id' => $placeId,
                    'available' => true,
                    'note' => 'Seeder: 10 największych miast',
                ]);
            }
        }
    }
}
