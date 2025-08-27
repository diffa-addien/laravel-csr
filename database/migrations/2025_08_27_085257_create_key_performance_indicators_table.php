<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_key_performance_indicators_table.php

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
        Schema::create('key_performance_indicators', function (Blueprint $table) {
            $table->id();
            $table->string('kategori'); // Kolom untuk kategori
            $table->string('metrik');   // Kolom untuk nama KPI
            $table->string('ukuran');   // Kolom untuk satuan ukuran (misal: %, Jam, Rupiah)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('key_performance_indicators');
    }
};