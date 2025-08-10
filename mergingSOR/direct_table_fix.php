<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== BEZPOŚREDNIA NAPRAWA STRUKTURY TABEL ===\n\n";

// Lista problematycznych tabel w kolejności (ważne dla foreign keys)
$tablesToFix = [
    'users',
    'roles',
    'permissions',
    'tags',
    'places',
    'currencies',
    'buses',
    'markups',
    'insurances',
    'hotel_rooms',
    'contractors',
    'contacts',
    'payment_types',
    'payment_statuses',
    'payers',
    'task_statuses',
    'todo_statuses',
    'event_templates',  // przed events
    'events',
    'event_template_qties',
    'event_template_program_points',  // przed tabelami które referencują
    'event_template_program_point_parent',
    'event_template_program_point_tag',
    'event_template_tag',
    'event_template_hotel_days',
    'event_template_day_insurance',
    'event_template_price_per_person',
    'event_program_points',
    'event_snapshots',
    'event_histories',
    'tasks',
    'task_comments',
    'task_attachments',
    'conversations',
    'conversation_participants',
    'messages',
    'kategoria_szablonus',
    'contact_contractor',
    'hotel_room_tag'
];

// Wyłącz foreign keys na początku
DB::statement('PRAGMA foreign_keys = OFF');
echo "Wyłączono foreign key constraints\n\n";

$successCount = 0;
$errorCount = 0;

foreach ($tablesToFix as $tableName) {
    echo "Naprawiam tabelę: {$tableName}\n";

    try {
        // Pobierz aktualną strukturę
        $tableInfo = DB::select("PRAGMA table_info({$tableName})");
        $createSql = DB::select("SELECT sql FROM sqlite_master WHERE type='table' AND name='{$tableName}'")[0]->sql;

        // Sprawdź czy tabela już ma PRIMARY KEY AUTOINCREMENT
        if (stripos($createSql, 'PRIMARY KEY AUTOINCREMENT') !== false) {
            echo "  ✅ Tabela już ma poprawną strukturę\n\n";
            $successCount++;
            continue;
        }

        // Utwórz listę kolumn z nową definicją id
        $columns = [];
        foreach ($tableInfo as $column) {
            if ($column->name === 'id') {
                $columns[] = '"id" INTEGER PRIMARY KEY AUTOINCREMENT';
            } else {
                $type = $column->type ?: 'TEXT';
                $nullable = $column->notnull ? ' NOT NULL' : '';
                $default = '';
                if ($column->dflt_value !== null) {
                    if (is_numeric($column->dflt_value)) {
                        $default = ' DEFAULT ' . $column->dflt_value;
                    } else {
                        $default = ' DEFAULT ' . $column->dflt_value;
                    }
                }
                $columns[] = "\"{$column->name}\" {$type}{$nullable}{$default}";
            }
        }

        // Utwórz nową tabelę
        $newTableSql = "CREATE TABLE \"{$tableName}_new\" (\n  " . implode(",\n  ", $columns) . "\n)";

        DB::statement($newTableSql);
        echo "  ➤ Utworzono nową tabelę\n";

        // Skopiuj dane
        DB::statement("INSERT INTO {$tableName}_new SELECT * FROM {$tableName}");
        echo "  ➤ Skopiowano dane\n";

        // Usuń starą tabelę
        DB::statement("DROP TABLE {$tableName}");
        echo "  ➤ Usunięto starą tabelę\n";

        // Zmień nazwę
        DB::statement("ALTER TABLE {$tableName}_new RENAME TO {$tableName}");
        echo "  ➤ Zmieniono nazwę tabeli\n";

        // Sprawdź czy struktura jest poprawna
        $newCreateSql = DB::select("SELECT sql FROM sqlite_master WHERE type='table' AND name='{$tableName}'")[0]->sql;
        if (stripos($newCreateSql, 'PRIMARY KEY AUTOINCREMENT') !== false) {
            echo "  ✅ Struktura naprawiona pomyślnie\n\n";
            $successCount++;
        } else {
            echo "  ❌ Błąd - struktura nie została naprawiona\n\n";
            $errorCount++;
        }
    } catch (Exception $e) {
        echo "  ❌ Błąd: " . $e->getMessage() . "\n\n";
        $errorCount++;

        // Spróbuj oczyścić tabelę tymczasową w przypadku błędu
        try {
            DB::statement("DROP TABLE IF EXISTS {$tableName}_new");
        } catch (Exception $cleanup) {
            // Ignoruj błędy oczyszczania
        }
    }
}

// Włącz foreign keys z powrotem
DB::statement('PRAGMA foreign_keys = ON');
echo "Włączono foreign key constraints\n\n";

echo "=== PODSUMOWANIE ===\n";
echo "Naprawiono pomyślnie: {$successCount} tabel\n";
echo "Błędy: {$errorCount} tabel\n";

if ($errorCount === 0) {
    echo "🎉 Wszystkie tabele zostały naprawione pomyślnie!\n";
    echo "Teraz możesz bezpiecznie używać klonowania szablonów eventów.\n";
} else {
    echo "⚠️  Niektóre tabele wymagają dodatkowej uwagi.\n";
}

echo "\n=== SPRAWDZENIE KOŃCOWE ===\n";
// Sprawdź kilka kluczowych tabel
$testTables = ['users', 'event_templates', 'event_template_program_points'];
foreach ($testTables as $testTable) {
    $sql = DB::select("SELECT sql FROM sqlite_master WHERE type='table' AND name='{$testTable}'")[0]->sql;
    $hasAutoincrement = stripos($sql, 'PRIMARY KEY AUTOINCREMENT') !== false;
    echo "{$testTable}: " . ($hasAutoincrement ? '✅ OK' : '❌ Problem') . "\n";
}
