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
        Schema::create('stkholder_pelaksanaan_ppks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('stkholder_perencanaan_program_anggarans')->onDelete('cascade');
            $table->unsignedBigInteger('pelaksana_id');
            $table->string('pelaksana_type');
            $table->enum('coverage', ['desa', 'kecamatan', 'kabupaten', 'provinsi'])->nullable();
            $table->string('kategori')->nullable();
            $table->string('karakter')->nullable();
            $table->unsignedBigInteger('biaya');
            $table->date('tanggal_pelaksanaan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stkholder_pelaksanaan_ppks');
    }
};
