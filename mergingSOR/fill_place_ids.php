<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

// Database configuration
$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'sqlite',
    'database' => __DIR__ . '/database/database.sqlite',
    'prefix' => '',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

try {
    // Get event templates that don't have start_place_id filled
    $emptyTemplates = Capsule::table('event_templates')
        ->whereNull('start_place_id')
        ->get();

    echo "Found " . $emptyTemplates->count() . " event templates without start_place_id\n";

    // Available place IDs (you can adjust these based on your places table)
    $placeIds = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20];

    $updated = 0;
    foreach ($emptyTemplates as $template) {
        // Pick a random place ID
        $randomPlaceId = $placeIds[array_rand($placeIds)];
        
        // Update both start_place_id and end_place_id with the same value
        Capsule::table('event_templates')
            ->where('id', $template->id)
            ->update([
                'start_place_id' => $randomPlaceId,
                'end_place_id' => $randomPlaceId,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        
        echo "Updated event_template ID {$template->id} ('{$template->name}') with place_id: {$randomPlaceId}\n";
        $updated++;
    }

    echo "\nSuccessfully updated {$updated} event templates\n";

    // Verify the results
    $totalCount = Capsule::table('event_templates')->count();
    $filledCount = Capsule::table('event_templates')->whereNotNull('start_place_id')->count();
    
    echo "\nVerification:\n";
    echo "Total event templates: {$totalCount}\n";
    echo "With start_place_id filled: {$filledCount}\n";
    echo "Missing start_place_id: " . ($totalCount - $filledCount) . "\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
