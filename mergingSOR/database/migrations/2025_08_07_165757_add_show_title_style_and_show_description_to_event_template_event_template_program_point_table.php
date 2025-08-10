<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('event_template_event_template_program_point', function (Blueprint $table) {
            $table->boolean('show_title_style')->default(true)->nullable()->after('active');
            $table->boolean('show_description')->default(true)->nullable()->after('show_title_style');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_template_event_template_program_point', function (Blueprint $table) {
            $table->dropColumn(['show_title_style', 'show_description']);
        });
    }
};
