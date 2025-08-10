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
    echo "Cleaning duplicate price records...\n";

    // Delete duplicates, keeping only the latest record for each combination
    $result = Capsule::statement("
        DELETE FROM event_template_price_per_person 
        WHERE id NOT IN (
            SELECT MAX(id) 
            FROM event_template_price_per_person 
            GROUP BY event_template_id, event_template_qty_id, start_place_id
        )
    ");

    echo "Duplicates removed.\n";

    // Verify results
    $totalRecords = Capsule::table('event_template_price_per_person')->count();
    echo "Total remaining records: {$totalRecords}\n";

    // Check for any remaining duplicates
    $duplicates = Capsule::select("
        SELECT COUNT(*) as duplicate_count
        FROM (
            SELECT event_template_id, event_template_qty_id, start_place_id, COUNT(*) as count
            FROM event_template_price_per_person 
            GROUP BY event_template_id, event_template_qty_id, start_place_id
            HAVING count > 1
        ) as duplicates
    ")[0]->duplicate_count ?? 0;

    echo "Remaining duplicates: {$duplicates}\n";

    if ($duplicates == 0) {
        echo "✅ All duplicates successfully removed!\n";
    } else {
        echo "⚠️ Some duplicates still remain.\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
