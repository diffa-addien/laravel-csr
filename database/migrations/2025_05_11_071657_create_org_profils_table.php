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
        Schema::create('org_profils', function (Blueprint $table) {
            $table->id();
            $table->string('logo')->nullable();
            $table->string('nama');
            $table->string('pimpinan');
            $table->string('lv1')->nullable();
            $table->string('lv2')->nullable();
            $table->string('lv3')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('org_profils');
    }
};
