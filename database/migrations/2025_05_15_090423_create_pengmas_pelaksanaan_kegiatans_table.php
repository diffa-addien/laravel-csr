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
        Schema::create('pengmas_pelaksanaan_kegiatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('pengmas_rencana_program_anggarans')->onDelete('cascade');
            $table->unsignedInteger('jumlah_penerima');
            $table->unsignedBigInteger('anggaran_pelaksanaan');
            $table->date('tanggal_pelaksanaan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengmas_pelaksanaan_kegiatans');
    }
};
