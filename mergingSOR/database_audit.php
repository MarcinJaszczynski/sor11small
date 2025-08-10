<?php

require_once 'vendor/autoload.php';

// Załaduj konfigurację Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== AUDYT STRUKTURY BAZY DANYCH ===\n\n";

// Pobierz wszystkie tabele
$tables = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");

$problematicTables = [];
$tablesWithNullIds = [];

foreach ($tables as $table) {
    $tableName = $table->name;
    echo "Sprawdzam tabelę: {$tableName}\n";

    // Sprawdź strukturę tabeli
    $tableInfo = DB::select("PRAGMA table_info({$tableName})");

    $hasIdColumn = false;
    $idIsPrimaryKey = false;
    $idHasAutoincrement = false;

    foreach ($tableInfo as $column) {
        if ($column->name === 'id') {
            $hasIdColumn = true;
            $idIsPrimaryKey = (bool)$column->pk;

            // Sprawdź czy ma AUTOINCREMENT
            $createTable = DB::select("SELECT sql FROM sqlite_master WHERE type='table' AND name='{$tableName}'")[0]->sql;
            $idHasAutoincrement = stripos($createTable, 'AUTOINCREMENT') !== false;

            break;
        }
    }

    if ($hasIdColumn) {
        if (!$idIsPrimaryKey || !$idHasAutoincrement) {
            $problematicTables[] = [
                'table' => $tableName,
                'has_id' => $hasIdColumn,
                'id_is_pk' => $idIsPrimaryKey,
                'id_has_autoincrement' => $idHasAutoincrement
            ];
            echo "  ❌ PROBLEM: ";
            if (!$idIsPrimaryKey) echo "id nie jest PRIMARY KEY ";
            if (!$idHasAutoincrement) echo "id nie ma AUTOINCREMENT ";
            echo "\n";
        } else {
            echo "  ✅ OK\n";
        }

        // Sprawdź czy są rekordy z NULL lub 0 w id
        try {
            $nullOrZeroCount = DB::select("SELECT COUNT(*) as count FROM {$tableName} WHERE id IS NULL OR id = 0")[0]->count;
            if ($nullOrZeroCount > 0) {
                $tablesWithNullIds[] = [
                    'table' => $tableName,
                    'count' => $nullOrZeroCount
                ];
                echo "  ⚠️  UWAGA: {$nullOrZeroCount} rekordów z NULL/0 w kolumnie id\n";
            }
        } catch (Exception $e) {
            echo "  ⚠️  Błąd sprawdzania rekordów: " . $e->getMessage() . "\n";
        }
    } else {
        echo "  ℹ️  Brak kolumny 'id'\n";
    }

    echo "\n";
}

echo "\n=== PODSUMOWANIE ===\n\n";

if (empty($problematicTables)) {
    echo "✅ Wszystkie tabele mają poprawną strukturę PRIMARY KEY AUTOINCREMENT!\n";
} else {
    echo "❌ Tabele wymagające naprawy:\n";
    foreach ($problematicTables as $table) {
        echo "- {$table['table']}: ";
        $issues = [];
        if (!$table['id_is_pk']) $issues[] = "brak PRIMARY KEY";
        if (!$table['id_has_autoincrement']) $issues[] = "brak AUTOINCREMENT";
        echo implode(', ', $issues) . "\n";
    }
}

if (!empty($tablesWithNullIds)) {
    echo "\n⚠️  Tabele z rekordami NULL/0 w id:\n";
    foreach ($tablesWithNullIds as $table) {
        echo "- {$table['table']}: {$table['count']} rekordów\n";
    }
}

echo "\n=== SZCZEGÓŁY PROBLEMATYCZNYCH TABEL ===\n";

foreach ($problematicTables as $table) {
    $tableName = $table['table'];
    echo "\nTabela: {$tableName}\n";
    echo "Aktualna struktura:\n";

    $createTable = DB::select("SELECT sql FROM sqlite_master WHERE type='table' AND name='{$tableName}'")[0]->sql;
    echo $createTable . "\n";

    // Sprawdź przykładowe rekordy
    try {
        $sampleRecords = DB::select("SELECT * FROM {$tableName} LIMIT 3");
        echo "Przykładowe rekordy:\n";
        foreach ($sampleRecords as $record) {
            echo "  ID: " . ($record->id ?? 'NULL') . "\n";
        }
    } catch (Exception $e) {
        echo "Błąd pobierania rekordów: " . $e->getMessage() . "\n";
    }

    echo "---\n";
}
