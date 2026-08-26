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
        // Create soal_sessions table untuk tracking session soal guru
        Schema::create('soal_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('soal_id');
            $table->unsignedBigInteger('user_id'); // guru
            $table->string('session_code')->unique(); // kode unik untuk akses murid
            $table->text('kode_akses')->nullable(); // info tambahan
            $table->timestamp('mulai_at');
            $table->timestamp('selesai_at')->nullable();
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->timestamps();

            $table->foreign('soal_id')->references('id')->on('soal')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Modify jawaban table untuk support session-based answers
        Schema::table('jawaban', function (Blueprint $table) {
            $table->unsignedBigInteger('session_id')->nullable()->after('soal_id');
            $table->string('nama_murid')->nullable()->after('user_id'); // nama murid tanpa login
            $table->string('identifier')->nullable()->after('nama_murid'); // identitas unik murid (dari session)
            
            $table->foreign('session_id')->references('id')->on('soal_sessions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jawaban', function (Blueprint $table) {
            $table->dropForeign(['session_id']);
            $table->dropColumn(['session_id', 'nama_murid', 'identifier']);
        });

        Schema::dropIfExists('soal_sessions');
    }
};
