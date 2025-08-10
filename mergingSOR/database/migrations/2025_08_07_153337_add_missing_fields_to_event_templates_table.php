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
        Schema::table('event_templates', function (Blueprint $table) {
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->unsignedBigInteger('event_price_description_id')->nullable();
            
            $table->foreign('event_price_description_id')
                  ->references('id')
                  ->on('event_price_descriptions')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_templates', function (Blueprint $table) {
            $table->dropForeign(['event_price_description_id']);
            $table->dropColumn(['short_description', 'description', 'event_price_description_id']);
        });
    }
};
