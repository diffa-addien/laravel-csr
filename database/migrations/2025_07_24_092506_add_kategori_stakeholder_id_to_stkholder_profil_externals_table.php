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
        Schema::table('stkholder_profil_externals', function (Blueprint $table) {
            // Menambahkan foreign key ke tabel kategori_stakeholder
            $table->foreignId('kategori_stakeholder_id')
                  ->nullable() // Bisa kosong
                  ->after('id') // Posisi kolom setelah 'id'
                  ->constrained('kategori_stakeholder') // Nama tabel referensi
                  ->onUpdate('cascade') // Jika id di tabel induk berubah, update di sini
                  ->onDelete('set null'); // Jika data di tabel induk dihapus, set kolom ini menjadi NULL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stkholder_profil_externals', function (Blueprint $table) {
            // Menghapus relasi dan kolom jika migrasi di-rollback
            $table->dropForeign(['kategori_stakeholder_id']);
            $table->dropColumn('kategori_stakeholder_id');
        });
    }
};
