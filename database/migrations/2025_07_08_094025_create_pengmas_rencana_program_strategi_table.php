<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengmas_rencana_program_strategi', function (Blueprint $table) {
            $table->id();
            // Gunakan nama kolom yang lebih panjang dan deskriptif
            $table->foreignId('pengmas_rencana_program_anggaran_id');
            $table->foreignId('strategi_id');
            $table->timestamps();

            // Definisikan constraint dengan nama pendek kustom
            $table->foreign(
                'pengmas_rencana_program_anggaran_id',
                'fk_pengmas_program_strategi_id' // Nama pendek
            )->references('id')->on('pengmas_rencana_program_anggarans')->onDelete('cascade');

            $table->foreign(
                'strategi_id',
                'fk_strategi_pengmas_program_id' // Nama pendek
            )->references('id')->on('strategis')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengmas_rencana_program_strategi');
    }
};