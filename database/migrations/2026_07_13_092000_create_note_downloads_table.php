<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('note_downloads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('note_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            // One row per user/note pair; re-downloads just refresh the timestamp.
            $table->unique(['user_id', 'note_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('note_downloads');
    }
};
