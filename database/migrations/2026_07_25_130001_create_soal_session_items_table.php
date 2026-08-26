<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('soal_sessions')) {
            $foreignKeys = DB::select("SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'soal_sessions' AND COLUMN_NAME = 'soal_id' AND REFERENCED_TABLE_NAME IS NOT NULL");

            foreach ($foreignKeys as $foreignKey) {
                DB::statement('ALTER TABLE soal_sessions DROP FOREIGN KEY ' . $foreignKey->CONSTRAINT_NAME);
            }
        }

        if (!Schema::hasTable('soal_session_items')) {
            Schema::create('soal_session_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('session_id');
                $table->unsignedBigInteger('soal_id');
                $table->integer('urutan');
                $table->timestamps();

                $table->foreign('session_id')->references('id')->on('soal_sessions')->onDelete('cascade');
                $table->foreign('soal_id')->references('id')->on('soal')->onDelete('cascade');
                $table->unique(['session_id', 'soal_id']);
            });
        }

        if (Schema::hasTable('soal_sessions')) {
            Schema::table('soal_sessions', function (Blueprint $table) {
                if (!Schema::hasColumn('soal_sessions', 'difficulty_type')) {
                    $table->enum('difficulty_type', ['mudah', 'normal', 'sulit', 'campuran'])->default('normal')->after('status');
                }

                if (Schema::hasColumn('soal_sessions', 'soal_id')) {
                    $table->dropColumn('soal_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('soal_sessions')) {
            Schema::table('soal_sessions', function (Blueprint $table) {
                if (!Schema::hasColumn('soal_sessions', 'soal_id')) {
                    $table->unsignedBigInteger('soal_id')->nullable();
                }

                if (Schema::hasColumn('soal_sessions', 'difficulty_type')) {
                    $table->dropColumn('difficulty_type');
                }

                try {
                    $table->foreign('soal_id')->references('id')->on('soal')->onDelete('cascade');
                } catch (\Throwable $e) {
                    // Abaikan jika foreign key sudah ada
                }
            });
        }

        Schema::dropIfExists('soal_session_items');
    }
};
