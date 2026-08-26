<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jawaban', function (Blueprint $table) {
            // Make user_id nullable for guest students (no login)
            $table->foreignId('user_id')->nullable()->change();

            // Drop old unique constraint on (user_id, soal_id)
            $table->dropUnique(['user_id', 'soal_id']);

            // Add proper unique constraint for session-based answers
            $table->unique(['session_id', 'soal_id', 'identifier']);
        });
    }

    public function down(): void
    {
        Schema::table('jawaban', function (Blueprint $table) {
            $table->dropUnique(['session_id', 'soal_id', 'identifier']);
            $table->unique(['user_id', 'soal_id']);
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};
