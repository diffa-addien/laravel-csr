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
            $table->foreignId('bidang_id')->constrained('bidangs')->onDelete('cascade');
            $table->string('nama_program');
            $table->string('jenis_program');
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('pengajuan_anggaran');
            $table->unsignedBigInteger('kesepakatan_anggaran')->nullable();
            $table->date('rencana_mulai');
            $table->date('rencana_selesai');
            $table->unsignedInteger('output');
            $table->string('output_unit');
            $table->text('tujuan_utama');
            $table->text('tujuan_khusus');
            $table->text('justifikasi');
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
