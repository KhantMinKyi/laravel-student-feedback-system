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
        'learning_year',
        'learning_year_second_semester',
        'feedback_total_percentage_comment',
    ];
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    public function year()
    {
        return $this->belongsTo(Year::class, 'year_id');
    }
}
