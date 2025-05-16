<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('org_penugasans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regional_id')->constrained('regionals')->onDelete('cascade');
            $table->string('petugas');
            $table->unsignedTinyInteger('jabatan');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('org_penugasans');
    }
};