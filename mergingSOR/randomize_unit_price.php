<?php
// Uruchom ten plik przez: php randomize_unit_price.php

require __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Losuj unit_price dla każdego EventTemplateProgramPoint
foreach (DB::table('event_template_program_points')->get() as $point) {
    $newPrice = rand(25, 125);
    DB::table('event_template_program_points')->where('id', $point->id)->update(['unit_price' => $newPrice]);
    echo "ID: {$point->id} -> unit_price: {$newPrice}\n";
}

echo "Losowe ceny zostały nadane.\n";
