<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_xxxxxx_add_anggaran_and_rencana_to_kompumed_rencana_programs_table.php
    public function up(): void
    {
        Schema::table('kompumed_rencana_programs', function (Blueprint $table) {
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
        Schema::table('kompumed_rencana_programs', function (Blueprint $table) {
            //
        });
    }
};
