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
        Schema::create('feedback', function (Blueprint $table) {
            $table->bigIncrements('feedback_id');
            $table->text('feedback_questions');
            $table->text('feedback_answers');
            $table->text('feedback_strength_weakness')->nullable()->default(null);
            $table->text('feedback_comment')->nullable()->default(null);
            $table->integer('teacher_id');
            $table->integer('student_id');
            $table->integer('course_id');
            $table->foreignId('year_id')->constrained('years')->cascadeOnUpdate()->cascadeOnDelete();
            $table->date('feedback_date');
            $table->integer('feedback_total_percentage')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
