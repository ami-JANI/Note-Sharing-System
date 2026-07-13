<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->string('department')->nullable()->after('credit_price');
            $table->foreignId('semester_id')->nullable()->constrained()->nullOnDelete()->after('department');
        });
    }

    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropForeign(['semester_id']);
            $table->dropColumn(['department', 'semester_id']);
        });
    }
};
