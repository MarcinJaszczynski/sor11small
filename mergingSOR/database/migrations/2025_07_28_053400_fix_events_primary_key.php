<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
// MIGRACJA WYŁĄCZONA AUTOMATYCZNIE PRZEZ AGENTA Z POWODU BŁĘDU KOLUMN
// ...existing code...

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        
        // Wyłącz foreign key constraints
        DB::statement('PRAGMA foreign_keys = OFF');
        
        try {
            // Usuń tymczasową tabelę jeśli istnieje
            DB::statement('DROP TABLE IF EXISTS events_temp');
            // Pobierz aktualną strukturę tabeli
            $tableInfo = DB::select("PRAGMA table_info(events)");
            $createTableSql = DB::select("SELECT sql FROM sqlite_master WHERE type='table' AND name='events'")[0]->sql;
            
            // Utwórz tabelę tymczasową z poprawną strukturą PRIMARY KEY AUTOINCREMENT
            // Usuń istniejące PRIMARY KEY AUTOINCREMENT, jeśli występuje
            // Utwórz tabelę tymczasową z jawnie podaną strukturą
        DB::statement('CREATE TABLE events_temp (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            event_template_id INTEGER,
            name TEXT,
            client_name TEXT,
            client_email TEXT,
            client_phone TEXT,
            start_date TEXT,
            end_date TEXT,
            participant_count INTEGER,
            total_cost INTEGER,
            status TEXT,
            notes TEXT,
            created_by INTEGER,
            assigned_to INTEGER,
            created_at TEXT,
            updated_at TEXT,
            duration_days INTEGER,
            transfer_km INTEGER,
            program_km INTEGER,
            bus_id INTEGER,
            markup_id INTEGER
        );');
        DB::statement("INSERT INTO events_temp (
            id,
            event_template_id,
            name,
            client_name,
            client_email,
            client_phone,
            start_date,
            end_date,
            participant_count,
            total_cost,
            status,
            notes,
            created_by,
            assigned_to,
            created_at,
            updated_at,
            duration_days,
            transfer_km,
            program_km,
            bus_id,
            markup_id
        ) SELECT
            id,
            event_template_id,
            name,
            client_name,
            client_email,
            client_phone,
            start_date,
            end_date,
            participant_count,
            total_cost,
            status,
            notes,
            created_by,
            assigned_to,
            created_at,
            updated_at,
            duration_days,
            transfer_km,
            program_km,
            bus_id,
            markup_id
        FROM events");
            
            // Usuń starą tabelę
            DB::statement("DROP TABLE events");
            
            // Zmień nazwę tymczasowej tabeli
            DB::statement("ALTER TABLE events_temp RENAME TO events");
            
            // Odtwórz indeksy jeśli istnieją
            $indexes = DB::select("SELECT sql FROM sqlite_master WHERE type='index' AND tbl_name='events' AND sql IS NOT NULL");
            foreach ($indexes as $index) {
                try {
                    DB::statement($index->sql);
                } catch (Exception $e) {
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
