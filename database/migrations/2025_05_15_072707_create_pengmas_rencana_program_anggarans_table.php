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
        Schema::create('pengmas_rencana_program_anggarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regional_id')->constrained('regionals')->onDelete('cascade');
            // jejak hapus relasi bidang
            $table->string('nama_program');
            $table->string('jenis_program')->nullable();
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('pengajuan_anggaran');
            $table->unsignedBigInteger('kesepakatan_anggaran')->nullable();
            $table->date('rencana_mulai');
            $table->date('rencana_selesai');
            $table->string('output')->nullable();
            $table->string('output_unit')->nullable();
            $table->text('tujuan_utama');
            $table->text('tujuan_khusus');
            $table->text('justifikasi');
            $table->foreignId('tahun_fiskal')->constrained('tahun_fiskals')->onDelete('restrict')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengmas_rencana_program_anggarans');
    }
};
