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
        Schema::create('student_years', function (Blueprint $table) {
            $table->bigIncrements('student_year_id');
            $table->foreignId('student_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('year_id')->constrained('years')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('role_number');
            $table->year('learning_year');
            $table->year('learning_year_second_semester');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_years');
    }
};
