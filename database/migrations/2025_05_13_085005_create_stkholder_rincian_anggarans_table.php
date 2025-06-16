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
        Schema::create('stkholder_rincian_anggarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('stkholder_perencanaan_program_anggarans')->onDelete('restrict');
            $table->string('pelaksana_id');
            $table->unsignedInteger('frekuensi');
            $table->string('frekuensi_unit');
            $table->unsignedBigInteger('biaya');
            $table->unsignedInteger('kuantitas');
            $table->string('kuantitas_unit');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stkholder_rincian_anggarans');
    }
};
