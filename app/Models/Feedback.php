<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;
    protected $primaryKey = 'feedback_id';
    protected $fillable = [
        'feedback_questions',
        'feedback_answers',
        'feedback_strength_weakness',
        'feedback_comment',
        'teacher_id',
        'student_id',
        'course_id',
        'year_id',
        'feedback_date',
        'feedback_total_percentage',
    ];
}
