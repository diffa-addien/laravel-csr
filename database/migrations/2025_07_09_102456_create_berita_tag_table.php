<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_berita_tag_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berita_tag', function (Blueprint $table) {
            $table->foreignId('berita_id')->constrained()->onDelete('cascade');
            $table->foreignId('tag_id')->constrained()->onDelete('cascade');
            $table->primary(['berita_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berita_tag');
    }
};