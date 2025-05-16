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
        Schema::create('pengmas_analisis_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_strategi')->constrained('strategis')->onDelete('cascade');
            $table->foreignId('id_program')->constrained('pengmas_rencana_program_anggarans')->onDelete('cascade');
            $table->text('target_hasil');
            $table->text('indikator_berhasil');
            $table->text('asumsi_or_risiko');
            $table->text('pendukung_hasil');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengmas_analisis_programs');
    }
};
