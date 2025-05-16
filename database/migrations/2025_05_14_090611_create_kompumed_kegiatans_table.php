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
        Schema::create('kompumed_kegiatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regional_id')->constrained('regionals')->onDelete('cascade');
            $table->foreignId('program_id')->constrained('kompumed_rencana_programs')->onDelete('cascade');
            $table->string('nama');
            $table->text('keterangan')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kompumed_kegiatans');
    }
};
