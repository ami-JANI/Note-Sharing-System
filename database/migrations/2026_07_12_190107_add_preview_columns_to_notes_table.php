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
        Schema::table('notes', function (Blueprint $table) {
            // First-page image shown on note cards.
            $table->string('preview_image_path')->nullable()->after('file_path');
            // Paths of the first N page images shown on the note detail page.
            $table->json('preview_pages')->nullable()->after('preview_image_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropColumn(['preview_image_path', 'preview_pages']);
        });
    }
};
