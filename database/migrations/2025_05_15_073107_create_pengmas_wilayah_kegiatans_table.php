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
        Schema::create('pengmas_wilayah_kegiatans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kegiatan')->nullable();
            $table->foreignId('id_desa')->constrained('desas')->onDelete('cascade');
            $table->foreignId('program_id')->constrained('pengmas_rencana_program_anggarans')->onDelete('cascade');
            $table->foreignId('bidang_id')->constrained('bidangs')->onDelete('restrict');
            $table->unsignedBigInteger('anggaran');
            $table->string('alamat')->nullable();
            $table->date('rencana_mulai')->nullable();
            $table->date('rencana_selesai')->nullable();
            $table->unsignedInteger('jumlah_penerima')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengmas_wilayah_kegiatans');
    }
};
