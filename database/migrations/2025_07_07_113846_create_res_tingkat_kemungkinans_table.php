<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ris_tingkat_kemungkinans', function (Blueprint $table) {
            $table->id();
            $table->integer('tingkat')->unique();
            $table->string('kemungkinan_risiko');
            $table->text('deskripsi')->nullable();
            $table->text('kriteria_kualitatif')->nullable();
            $table->text('kriteria_kuantitatif')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('res_tingkat_kemungkinans');
    }
};