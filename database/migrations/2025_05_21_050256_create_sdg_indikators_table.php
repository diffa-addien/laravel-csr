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
        Schema::create('sdg_indikators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('target_id')->constrained('sdg_targets')->onDelete('restrict');
            $table->string('no_indikator')->unique();
            $table->string('nama_indikator');
            $table->text('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sdg_indikators');
    }
};
