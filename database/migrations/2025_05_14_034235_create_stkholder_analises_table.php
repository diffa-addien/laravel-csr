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
        Schema::create('stkholder_analisis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('stkholder_perencanaan_program_anggarans')->onDelete('cascade');
            $table->text('deskripsi')->nullable();
            $table->text('target_hasil')->nullable();
            $table->text('indikator_berhasil')->nullable();
            $table->text('asumsi_or_risiko')->nullable();
            $table->text('pendukung_hasil')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stkholder_analisis');
    }
};
