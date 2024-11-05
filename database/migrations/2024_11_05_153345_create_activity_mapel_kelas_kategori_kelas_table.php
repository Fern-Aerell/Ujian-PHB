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
        Schema::create('activity_mapel_kelas_kategori_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained()->cascadeOnDelete();
            $table->foreignId('mapel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('kelas_kategori_id')->constrained()->cascadeOnDelete();
            $table->foreignId('kelas_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['mapel_id', 'kelas_kategori_id', 'kelas_id'], 'three_unique_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_mapel_kelas_kategori_kelas');
    }
};
