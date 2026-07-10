<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('credits')->default(0)->after('password');
            $table->enum('status', ['active', 'suspended'])->default('active')->after('credits');
            $table->string('photo')->nullable()->after('status');
            $table->string('roll')->nullable()->after('photo');
            $table->foreignId('current_semester_id')->nullable()->constrained('semesters')->nullOnDelete()->after('roll');
        });

        Schema::table('notes', function (Blueprint $table) {
            $table->unsignedInteger('credit_price')->default(0)->after('file_path');
        });

        Schema::create('note_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('note_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('credits_spent');
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('credits_purchased');
            $table->string('status')->default('completed');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('note_purchases');

        Schema::table('notes', function (Blueprint $table) {
            $table->dropColumn('credit_price');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['current_semester_id']);
            $table->dropColumn(['credits', 'status', 'photo', 'roll', 'current_semester_id']);
        });
    }
};
