<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tahun_fiskals', function (Blueprint $table) {
            $table->id();
            $table->string('tahun_fiskal')->unique(); // Misalnya: "2023/2024"
            $table->date('tanggal_buka');
            $table->date('tanggal_tutup');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahun_fiskals');
    }
};
