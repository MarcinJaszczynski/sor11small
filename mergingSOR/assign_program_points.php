<?php
// Uruchom ten plik przez: php assign_program_points.php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Pobierz wszystkie event_template_id
$templates = DB::table('event_templates')->pluck('id');
$points = DB::table('event_template_program_points')->pluck('id');

foreach ($templates as $templateId) {
    // Pobierz wszystkie dni dla event_template_id
    $days = DB::table('event_template_event_template_program_point')
        ->where('event_template_id', $templateId)
        ->pluck('day')->unique();
    if ($days->isEmpty()) {
        // Jeśli nie ma żadnych dni, dodaj domyślnie 1
        $days = collect([1]);
    }
    foreach ($days as $day) {
        // Policz ile punktów jest już przypisanych do tego dnia
        $count = DB::table('event_template_event_template_program_point')
            ->where('event_template_id', $templateId)
            ->where('day', $day)
            ->count();
        $toAdd = max(0, 3 - $count);
        if ($toAdd > 0) {
            // Wybierz losowe punkty, które nie są jeszcze przypisane do tego dnia
            $usedPoints = DB::table('event_template_event_template_program_point')
                ->where('event_template_id', $templateId)
                ->where('day', $day)
                ->pluck('event_template_program_point_id')->toArray();
            $availablePoints = collect($points)->diff($usedPoints)->shuffle()->take($toAdd);
            foreach ($availablePoints as $pointId) {
                DB::table('event_template_event_template_program_point')->insert([
                    'event_template_id' => $templateId,
                    'event_template_program_point_id' => $pointId,
                    'day' => $day,
                    'order' => rand(1, 100),
                    'include_in_program' => 1,
                    'include_in_calculation' => 1,
                    'active' => 1,
                    'show_title_style' => 1,
                    'show_description' => 1,
                ]);
                echo "Dodano punkt $pointId do template $templateId na dzień $day\n";
            }
        }
    }
}
echo "Przypisano punkty do wszystkich dni.\n";
