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
        Schema::create('stkholder_perencanaan_ppk_strategi', function (Blueprint $table) {
            $table->id();

            // Definisikan kolom foreign key seperti biasa
            $table->foreignId('stkholder_perencanaan_ppk_id');
            $table->foreignId('strategi_id');
            $table->timestamps();

            // Tambahkan constraint secara manual dengan nama yang lebih pendek
            $table->foreign('stkholder_perencanaan_ppk_id', 'fk_ppk_strategi_id') // Nama pendek kustom
                ->references('id')
                ->on('stkholder_perencanaan_ppks')
                ->onDelete('cascade');

            $table->foreign('strategi_id', 'fk_strategi_ppk_id') // Nama pendek kustom
                ->references('id')
                ->on('strategis')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stkholder_perencanaan_ppk_strategi');
    }
};
