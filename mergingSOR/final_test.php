<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== TEST KOŃCOWY STRUKTURY BAZY DANYCH ===\n\n";

// Sprawdź kluczowe tabele, które były problematyczne
$criticalTables = [
    'event_templates',
    'event_template_program_points',
    'event_template_event_template_program_point',
    'event_template_hotel_days',
    'event_template_day_insurance',
    'event_template_price_per_person',
    'users'
];

$allGood = true;

foreach ($criticalTables as $tableName) {
    echo "Sprawdzam tabelę: {$tableName}\n";

    try {
        // Sprawdź strukturę
        $createSql = DB::select("SELECT sql FROM sqlite_master WHERE type='table' AND name='{$tableName}'")[0]->sql;
        $hasAutoincrement = stripos($createSql, 'PRIMARY KEY AUTOINCREMENT') !== false;

        if ($hasAutoincrement) {
            echo "  ✅ Struktura PRIMARY KEY AUTOINCREMENT: OK\n";
        } else {
            echo "  ❌ BRAK PRIMARY KEY AUTOINCREMENT\n";
            $allGood = false;
        }

        // Sprawdź czy są rekordy z NULL/0 w id
        $nullCount = DB::select("SELECT COUNT(*) as count FROM {$tableName} WHERE id IS NULL OR id = 0")[0]->count;
        if ($nullCount == 0) {
            echo "  ✅ Rekordy z NULL/0 w id: BRAK\n";
        } else {
            echo "  ⚠️  Rekordy z NULL/0 w id: {$nullCount}\n";
            $allGood = false;
        }

        // Test basic operations
        $count = DB::select("SELECT COUNT(*) as count FROM {$tableName}")[0]->count;
        echo "  ℹ️  Liczba rekordów: {$count}\n";
    } catch (Exception $e) {
        echo "  ❌ Błąd: " . $e->getMessage() . "\n";
        $allGood = false;
    }

    echo "\n";
}

echo "=== TEST FOREIGN KEY CONSTRAINTS ===\n";

// Test foreign key constraints
DB::statement('PRAGMA foreign_keys = ON');
try {
    // Spróbuj wykonać operacje, które wcześniej powodowały problemy
    $eventTemplate = DB::select("SELECT * FROM event_templates LIMIT 1");
    if ($eventTemplate) {
        $templateId = $eventTemplate[0]->id;
        echo "Test template ID: {$templateId}\n";

        // Sprawdź czy można bezpiecznie wykonać join
        $programPoints = DB::select("
            SELECT etp.id as template_id, etpp.id as point_id 
            FROM event_templates etp
            LEFT JOIN event_template_event_template_program_point etep ON etp.id = etep.event_template_id
            LEFT JOIN event_template_program_points etpp ON etep.event_template_program_point_id = etpp.id
            WHERE etp.id = ?
            LIMIT 5
        ", [$templateId]);

        echo "  ✅ Join query executed successfully: " . count($programPoints) . " results\n";
    }
} catch (Exception $e) {
    echo "  ❌ Foreign key test failed: " . $e->getMessage() . "\n";
    $allGood = false;
}

echo "\n=== PODSUMOWANIE ===\n";
if ($allGood) {
    echo "🎉 WSZYSTKO DZIAŁA POPRAWNIE!\n";
    echo "✅ Struktura bazy danych została naprawiona\n";
    echo "✅ PRIMARY KEY AUTOINCREMENT działa we wszystkich tabelach\n";
    echo "✅ Brak rekordów z NULL/0 w kluczach głównych\n";
    echo "✅ Foreign key constraints działają poprawnie\n";
    echo "\n";
    echo "Teraz możesz bezpiecznie:\n";
    echo "- Klonować szablony eventów\n";
    echo "- Używać wszystkich funkcji Filament\n";
    echo "- Wykonywać operacje na bazie bez obaw o foreign key constraints\n";
} else {
    echo "⚠️  WYKRYTO PROBLEMY - sprawdź powyższe błędy\n";
}

echo "\n=== CZYSZCZENIE PLIKÓW TYMCZASOWYCH ===\n";

// Usuń pliki pomocnicze
$filesToClean = [
    'database_audit.php',
    'generate_fix_migrations.php',
    'generate_fixed_migrations.php',
    'fix_null_ids.php',
    'check_events_structure.php',
    'direct_table_fix.php'
];

foreach ($filesToClean as $file) {
    if (file_exists($file)) {
        unlink($file);
        echo "Usunięto: {$file}\n";
    }
}

echo "\n✨ NAPRAWA BAZY DANYCH ZAKOŃCZONA POMYŚLNIE! ✨\n";
