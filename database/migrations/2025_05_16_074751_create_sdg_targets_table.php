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
        Schema::create('sdg_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tujuan_id')->constrained('sdg_tujuans')->onDelete('restrict');
            $table->string('no_target')->unique();
            $table->text('target');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sdg_targets');
    }
};
