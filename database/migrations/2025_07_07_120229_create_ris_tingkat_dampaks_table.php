<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ris_tingkat_dampaks', function (Blueprint $table) {
            $table->id();
            $table->integer('tingkat')->unique();
            $table->string('dampak_risiko');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ris_tingkat_dampaks');
    }
};