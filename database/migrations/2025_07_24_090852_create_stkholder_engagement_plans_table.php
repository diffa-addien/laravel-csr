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
        Schema::create('stkholder_engagement_plan', function (Blueprint $table) {
            $table->id();

            // Diubah dari 'pelaksana' menjadi 'stakeholder'
            // Ini akan membuat kolom 'stakeholder_id' dan 'stakeholder_type'
            $table->morphs('stakeholder');

            $table->string('influence_level')->nullable();
            $table->string('interest_level')->nullable();
            $table->string('frequency')->nullable();
            $table->string('channel')->nullable();
            $table->string('info_type')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stkholder_engagement_plan');
    }
};
