<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$sql = DB::select("SELECT sql FROM sqlite_master WHERE type='table' AND name='events'")[0]->sql;
echo "Events table structure:\n";
echo $sql . "\n\n";

echo "Events table info:\n";
$info = DB::select("PRAGMA table_info(events)");
foreach ($info as $column) {
    echo "Column: {$column->name}, Type: {$column->type}, Primary Key: {$column->pk}\n";
}
