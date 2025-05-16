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
        Schema::create('kompumed_pelaksanaan_kegiatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('kompumed_kegiatans')->onDelete('cascade');
            $table->text('deskripsi')->nullable();
            $table->unsignedInteger('frekuensi');
            $table->string('frekuensi_unit');
            $table->unsignedInteger('kuantitas');
            $table->string('kuantitas_unit');
            $table->unsignedBigInteger('biaya');
            $table->date('tanggal_pelaksanaan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kompumed_pelaksanaan_kegiatans');
    }
};
