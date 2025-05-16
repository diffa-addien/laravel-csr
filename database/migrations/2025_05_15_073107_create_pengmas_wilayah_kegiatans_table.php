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
            $table->foreignId('id_desa')->constrained('desas')->onDelete('cascade');
            $table->foreignId('program_id')->constrained('pengmas_rencana_program_anggarans')->onDelete('cascade');
            $table->string('alamat')->nullable();
            $table->unsignedInteger('jumlah_penerima');
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
