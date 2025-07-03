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
        Schema::create('kuadrans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kuadran');
            $table->text('deskripsi')->nullable(); // Dibuat nullable jika deskripsi tidak wajib
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kuadrans');
    }
};