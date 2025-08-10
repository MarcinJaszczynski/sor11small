<?php

require_once 'vendor/autoload.php';

// Załaduj konfigurację Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== GENERATOR MIGRACJI NAPRAWCZYCH ===\n\n";

// Lista problematycznych tabel (wykluczając tabele systemowe, które nie muszą mieć AUTOINCREMENT)
$problematicTables = [
    // Główne tabele aplikacji
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

    // Tabele eventów i szablonów
    'events',
    'event_templates',
    'event_template_qties',
    'event_template_program_points',
    'event_template_program_point_parent',
    'event_template_program_point_tag',
    'event_template_tag',
    'event_template_hotel_days',
    'event_template_day_insurance',
    'event_template_price_per_person',
    'event_program_points',
    'event_snapshots',
    'event_histories',

    // Tabele zadań i komunikacji
    'tasks',
    'task_comments',
    'task_attachments',
    'conversations',
    'conversation_participants',
    'messages',

    // Inne tabele
    'kategoria_szablonus',
    'contact_contractor',
    'hotel_room_tag'
];

// Wyklucz tabele systemowe, które nie potrzebują AUTOINCREMENT
$systemTables = [
    'migrations',
    'sessions',
    'failed_jobs',
    'jobs',
    'job_batches',
    'transport_types',
    'contract_templates'
];

$tablesToFix = array_diff($problematicTables, $systemTables);

$timestamp = date('Y_m_d_His');

foreach ($tablesToFix as $index => $tableName) {
    $migrationTimestamp = date('Y_m_d_His', strtotime("+{$index} seconds"));
    $className = 'Fix' . str_replace('_', '', ucwords($tableName, '_')) . 'PrimaryKey';
    $fileName = "database/migrations/{$migrationTimestamp}_fix_{$tableName}_primary_key.php";

    echo "Tworzę migrację dla tabeli: {$tableName}\n";

    // Sprawdź czy tabela ma rekordy z NULL/0 w id
    $nullRecordsCount = 0;
    try {
        $result = DB::select("SELECT COUNT(*) as count FROM {$tableName} WHERE id IS NULL OR id = 0");
        $nullRecordsCount = $result[0]->count;
    } catch (Exception $e) {
        // Ignoruj błędy
    }

    $migrationContent = "<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {";

    if ($nullRecordsCount > 0) {
        $migrationContent .= "
        // Najpierw napraw rekordy z NULL/0 w id
        \$maxId = DB::table('{$tableName}')->max('id') ?: 0;
        \$nullRecords = DB::table('{$tableName}')->whereNull('id')->orWhere('id', 0)->get();
        
        foreach (\$nullRecords as \$index => \$record) {
            DB::table('{$tableName}')
                ->where('id', \$record->id)
                ->update(['id' => \$maxId + \$index + 1]);
        }";
    }

    $migrationContent .= "
        
        // Wyłącz foreign key constraints
        DB::statement('PRAGMA foreign_keys = OFF');
        
        try {
            // Pobierz aktualną strukturę tabeli
            \$tableInfo = DB::select(\"PRAGMA table_info({$tableName})\");
            \$createTableSql = DB::select(\"SELECT sql FROM sqlite_master WHERE type='table' AND name='{$tableName}'\")[0]->sql;
            
            // Utwórz tabelę tymczasową z poprawną strukturą PRIMARY KEY AUTOINCREMENT
            \$tempTableSql = str_replace(
                ['\"id\" INTEGER,', '\"id\" INTEGER'],
                '\"id\" INTEGER PRIMARY KEY AUTOINCREMENT,',
                \$createTableSql
            );
            \$tempTableSql = str_replace('CREATE TABLE \"{$tableName}\"', 'CREATE TABLE \"{$tableName}_temp\"', \$tempTableSql);
            
            DB::statement(\$tempTableSql);
            
            // Skopiuj dane do tymczasowej tabeli
            DB::statement(\"INSERT INTO {$tableName}_temp SELECT * FROM {$tableName}\");
            
            // Usuń starą tabelę
            DB::statement(\"DROP TABLE {$tableName}\");
            
            // Zmień nazwę tymczasowej tabeli
            DB::statement(\"ALTER TABLE {$tableName}_temp RENAME TO {$tableName}\");
            
            // Odtwórz indeksy jeśli istnieją
            \$indexes = DB::select(\"SELECT sql FROM sqlite_master WHERE type='index' AND tbl_name='{$tableName}' AND sql IS NOT NULL\");
            foreach (\$indexes as \$index) {
                try {
                    DB::statement(\$index->sql);
                } catch (Exception \$e) {
                    // Ignoruj błędy przy odtwarzaniu indeksów
                }
            }
            
        } finally {
            // Ponownie włącz foreign key constraints
            DB::statement('PRAGMA foreign_keys = ON');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Nie implementujemy rollback dla bezpieczeństwa danych
        // W razie potrzeby można przywrócić z kopii zapasowej
        throw new Exception('Cannot rollback this migration for data safety');
    }
};
";

    // Zapisz migrację do pliku
    file_put_contents($fileName, $migrationContent);
    echo "  ✅ Utworzono: {$fileName}\n";
}

echo "\n=== UTWORZENIE SKRYPTU NAPRAWCZEGO ===\n";

// Utwórz dodatkowy skrypt naprawczy dla tabel systemowych
$repairScript = "<?php

require_once 'vendor/autoload.php';

// Załaduj konfigurację Laravel
\$app = require_once 'bootstrap/app.php';
\$app->make(Illuminate\\Contracts\\Console\\Kernel::class)->bootstrap();

use Illuminate\\Support\\Facades\\DB;

echo \"=== NAPRAWA REKORDÓW Z NULL/0 W ID ===\\n\\n\";

// Tabele z rekordami NULL/0 w id (na podstawie audytu)
\$tablesWithNullIds = [
    'event_template_program_point_tag' => 1,
    'event_template_program_point_parent' => 4,
    'event_template_hotel_days' => 14,
    'event_template_day_insurance' => 59,
    'event_template_price_per_person' => 110,
    'migrations' => 12,
    'event_template_program_points' => 1,
];

foreach (\$tablesWithNullIds as \$tableName => \$expectedCount) {
    echo \"Naprawiam tabelę: {\$tableName}\\n\";
    
    try {
        // Sprawdź aktualne rekordy z problemami
        \$nullRecords = DB::select(\"SELECT COUNT(*) as count FROM {\$tableName} WHERE id IS NULL OR id = 0\");
        \$actualCount = \$nullRecords[0]->count;
        
        if (\$actualCount == 0) {
            echo \"  ✅ Tabela {\$tableName} nie ma problematycznych rekordów\\n\";
            continue;
        }
        
        echo \"  🔧 Znaleziono {\$actualCount} rekordów z NULL/0 w id\\n\";
        
        // Pobierz maksymalną wartość id
        \$maxId = DB::select(\"SELECT MAX(id) as max_id FROM {\$tableName} WHERE id IS NOT NULL AND id > 0\");
        \$startId = (\$maxId[0]->max_id ?? 0) + 1;
        
        // Pobierz rekordy z problemami i napraw je
        \$problematicRecords = DB::select(\"SELECT rowid FROM {\$tableName} WHERE id IS NULL OR id = 0\");
        
        foreach (\$problematicRecords as \$index => \$record) {
            \$newId = \$startId + \$index;
            DB::update(\"UPDATE {\$tableName} SET id = ? WHERE rowid = ?\", [\$newId, \$record->rowid]);
            echo \"    ➤ Zaktualizowano rekord rowid={\$record->rowid} na id={\$newId}\\n\";
        }
        
        echo \"  ✅ Naprawiono {\$actualCount} rekordów w tabeli {\$tableName}\\n\";
        
    } catch (Exception \$e) {
        echo \"  ❌ Błąd przy naprawie tabeli {\$tableName}: \" . \$e->getMessage() . \"\\n\";
    }
    
    echo \"\\n\";
}

echo \"=== NAPRAWA ZAKOŃCZONA ===\\n\";
echo \"Uruchom teraz migracje: php artisan migrate\\n\";
";

file_put_contents('fix_null_ids.php', $repairScript);
echo "✅ Utworzono skrypt naprawczy: fix_null_ids.php\n";

echo "\n=== INSTRUKCJE ===\n";
echo "1. Najpierw uruchom skrypt naprawczy: php fix_null_ids.php\n";
echo "2. Następnie uruchom migracje: php artisan migrate\n";
echo "3. Sprawdź czy wszystko działa poprawnie\n";
echo "4. Utwórz kopię zapasową bazy przed uruchomieniem!\n\n";

echo "Utworzono " . count($tablesToFix) . " migracji naprawczych.\n";
