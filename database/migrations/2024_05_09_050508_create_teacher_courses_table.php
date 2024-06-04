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
        Schema::create('teacher_courses', function (Blueprint $table) {
            $table->bigIncrements('teacher_course_id');
            $table->integer('course_id');
            $table->integer('teacher_id');
            $table->year('teaching_year');
            $table->year('teaching_year_second_semester');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_courses');
    }
};
