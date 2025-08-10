<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EventTemplate;
use App\Models\EventType;

class EventTemplateEventTypeSeeder extends Seeder
{
    /**
     * Przypisz każdemu EventTemplate losowy EventType
     */
    public function run(): void
    {
        // Pobierz wszystkie Event Types
        $eventTypes = EventType::all();
        
        if ($eventTypes->count() == 0) {
            $this->command->info('Brak Event Types w bazie danych');
            return;
        }
        
        $this->command->info('Dostępne Event Types: ' . $eventTypes->count());
        foreach($eventTypes as $type) {
            $this->command->info('- ' . $type->id . ': ' . $type->name);
        }
        
        // Pobierz wszystkie Event Templates
        $eventTemplates = EventTemplate::all();
        $this->command->info('Event Templates do przypisania: ' . $eventTemplates->count());
        
        $assigned = 0;
        foreach($eventTemplates as $template) {
            // Sprawdź, czy już ma przypisany Event Type
            if($template->eventTypes()->count() == 0) {
                // Losowo wybierz jeden z Event Types
                $randomEventType = $eventTypes->random();
                $template->eventTypes()->attach($randomEventType->id);
                $assigned++;
                $this->command->info('Przypisano "' . $randomEventType->name . '" do "' . $template->name . '"');
            } else {
                $this->command->info('Template "' . $template->name . '" już ma przypisany Event Type');
            }
        }
        
        $this->command->info('Przypisano Event Types do ' . $assigned . ' Event Templates');
    }
}
