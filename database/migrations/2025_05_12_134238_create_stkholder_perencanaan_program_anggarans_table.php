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
        Schema::create('stkholder_perencanaan_program_anggarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regional_id')->constrained('regionals')->onDelete('cascade');
            $table->foreignId('program_id')->constrained('stkholder_perencanaan_ppks')->onDelete('cascade');
            $table->string('kegiatan');
            $table->unsignedBigInteger('anggaran_pengajuan');
            $table->unsignedBigInteger('anggaran_kesepakatan')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stkholder_perencanaan_program_anggarans');
    }
};
