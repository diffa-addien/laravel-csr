<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ris_tingkat_risikos', function (Blueprint $table) {
            $table->id();
            $table->string('tingkat_risiko');
            $table->string('deskripsi');
            $table->text('petunjuk')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ris_tingkat_risikos');
    }
};