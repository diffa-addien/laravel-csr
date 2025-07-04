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
        Schema::create('bidang_sdg_tujuan', function (Blueprint $table) {
            // Kolom foreign key untuk model Bidang
            $table->foreignId('bidang_id')->constrained()->onDelete('restrict');
            
            // Kolom foreign key untuk model SdgTujuan
            $table->foreignId('sdg_tujuan_id')->constrained()->onDelete('restrict');

            // Menetapkan kedua kolom sebagai primary key
            // untuk mencegah duplikasi data (pasangan yang sama).
            $table->primary(['bidang_id', 'sdg_tujuan_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bidang_sdg_tujuan');
    }
};