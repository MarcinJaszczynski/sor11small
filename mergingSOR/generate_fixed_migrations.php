<?php

require_once 'vendor/autoload.php';

// Załaduj konfigurację Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== POPRAWIONY GENERATOR MIGRACJI NAPRAWCZYCH ===\n\n";

// Lista problematycznych tabel
$problematicTables = [
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

// Usuń stare migracje
$migrationPattern = 'database/migrations/2025_07_28_0533*_fix_*_primary_key.php';
$oldMigrations = glob($migrationPattern);
foreach ($oldMigrations as $migration) {
    unlink($migration);
    echo "Usunięto starą migrację: " . basename($migration) . "\n";
}

$timestamp = date('Y_m_d_His');

foreach ($problematicTables as $index => $tableName) {
    $migrationTimestamp = date('Y_m_d_His', strtotime("+{$index} seconds"));
    $className = 'Fix' . str_replace('_', '', ucwords($tableName, '_')) . 'PrimaryKey';
    $fileName = "database/migrations/{$migrationTimestamp}_fix_{$tableName}_primary_key.php";

    echo "Tworzę poprawioną migrację dla tabeli: {$tableName}\n";

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
    {
        // Wyłącz foreign key constraints
        DB::statement('PRAGMA foreign_keys = OFF');
        
        try {
            // Pobierz aktualną strukturę tabeli
            \$createTableSql = DB::select(\"SELECT sql FROM sqlite_master WHERE type='table' AND name='{$tableName}'\")[0]->sql;
            
            // Pobierz nazwy wszystkich kolumn
            \$columns = DB::select(\"PRAGMA table_info({$tableName})\");
            \$columnDefinitions = [];
            
            foreach (\$columns as \$column) {
                if (\$column->name === 'id') {
                    \$columnDefinitions[] = '\"id\" INTEGER PRIMARY KEY AUTOINCREMENT';
                } else {
                    \$type = \$column->type ?: 'TEXT';
                    \$nullable = \$column->notnull ? ' NOT NULL' : '';
                    \$default = \$column->dflt_value ? ' DEFAULT ' . \$column->dflt_value : '';
                    \$columnDefinitions[] = '\"' . \$column->name . '\" ' . \$type . \$nullable . \$default;
                }
            }
            
            // Utwórz tabelę tymczasową z poprawną strukturą
            \$tempTableSql = 'CREATE TABLE \"{$tableName}_temp\" (' . \"\\n  \" . implode(\",\\n  \", \$columnDefinitions) . \"\\n)\";
            
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
        throw new Exception('Cannot rollback this migration for data safety');
    }
};
";

    // Zapisz migrację do pliku
    file_put_contents($fileName, $migrationContent);
    echo "  ✅ Utworzono: {$fileName}\n";
}

echo "\nUtworzono " . count($problematicTables) . " poprawionych migracji naprawczych.\n";
echo "Uruchom teraz: php artisan migrate\n";
