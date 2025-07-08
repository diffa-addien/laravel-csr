<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_xxxxxx_create_kompumed_rencana_program_strategi_table.php
    public function up(): void
    {
        Schema::create('kompumed_rencana_program_strategi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kompumed_rencana_program_id');
            $table->foreignId('strategi_id');
            $table->timestamps();

            // Constraint dengan nama pendek kustom
            $table->foreign('kompumed_rencana_program_id', 'fk_kompumed_program_strategi_id')
                ->references('id')
                ->on('kompumed_rencana_programs')
                ->onDelete('cascade');

            $table->foreign('strategi_id', 'fk_strategi_kompumed_program_id')
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
        Schema::dropIfExists('kompumed_rencana_program_strategi');
    }
};
