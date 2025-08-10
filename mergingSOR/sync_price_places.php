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
    // Update all prices to have the same start_place_id as their event_template
    $result = Capsule::statement("
        UPDATE event_template_price_per_person 
        SET start_place_id = (
            SELECT start_place_id 
            FROM event_templates 
            WHERE event_templates.id = event_template_price_per_person.event_template_id
        )
        WHERE start_place_id IS NULL OR start_place_id != (
            SELECT start_place_id 
            FROM event_templates 
            WHERE event_templates.id = event_template_price_per_person.event_template_id
        )
    ");

    echo "Updated prices with matching start_place_id from event_templates\n";

    // Verify the results
    $totalPrices = Capsule::table('event_template_price_per_person')->count();
    $pricesWithStartPlace = Capsule::table('event_template_price_per_person')->whereNotNull('start_place_id')->count();
    
    echo "\nVerification:\n";
    echo "Total prices: {$totalPrices}\n";
    echo "Prices with start_place_id: {$pricesWithStartPlace}\n";
    echo "Missing start_place_id: " . ($totalPrices - $pricesWithStartPlace) . "\n";

    // Check if all prices now match their event_template's start_place_id
    $matchingPrices = Capsule::select("
        SELECT COUNT(*) as matching_count
        FROM event_template_price_per_person p
        JOIN event_templates e ON p.event_template_id = e.id
        WHERE p.start_place_id = e.start_place_id
    ")[0]->matching_count;

    echo "Prices matching event_template start_place_id: {$matchingPrices}\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
