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
        Schema::table('stkholder_perencanaan_ppks', function (Blueprint $table) {
            $table->decimal('pengajuan_anggaran', 15, 2)->nullable();
            $table->decimal('kesepakatan_anggaran', 15, 2)->nullable();
            $table->date('rencana_mulai')->nullable();
            $table->date('rencana_selesai')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stkholder_perencanaan_ppks', function (Blueprint $table) {
            //
        });
    }
};
