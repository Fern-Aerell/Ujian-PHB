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
        Schema::create('do_activity_soals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('do_activity_id')->constrained()->cascadeOnDelete();
            $table->foreignId('soal_id')->constrained()->cascadeOnDelete();
            $table->foreignId('jawaban_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('do_activity_soals');
    }
};
