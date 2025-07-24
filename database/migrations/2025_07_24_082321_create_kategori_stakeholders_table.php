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
        // Creates the 'kategori_stakeholder' table
        Schema::create('kategori_stakeholder', function (Blueprint $table) {
            // Primary key
            $table->id();

            // 'nama_kategori' column, type string. This column is mandatory.
            $table->string('nama_kategori');

            // 'deskripsi' column, type text. This column is optional (nullable).
            $table->text('deskripsi')->nullable();

            // 'created_at' and 'updated_at' timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drops the table if the migration is rolled back
        Schema::dropIfExists('kategori_stakeholder');
    }
};