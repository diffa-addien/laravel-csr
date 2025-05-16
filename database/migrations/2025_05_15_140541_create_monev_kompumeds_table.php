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
        Schema::create('monev_kompumeds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggaran_id')->unique()->constrained('kompumed_kegiatans')->onDelete('cascade');
            $table->unsignedBigInteger('nilai_evaluasi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monev_kompumeds');
    }
};
