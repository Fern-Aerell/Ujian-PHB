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
        Schema::create('configs', function (Blueprint $table) {
            $table->id();
            $table->string('logo');
            $table->string('school_name');
            $table->string('activity_type');
            $table->string('activity_title');
            $table->string('activity_title_abbreviation');
            $table->date('exam_date_start');
            $table->date('exam_date_end');
            $table->string('holiday_date')->nullable();
            $table->time('exam_time_start');
            $table->time('exam_time_end');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configs');
    }
};
