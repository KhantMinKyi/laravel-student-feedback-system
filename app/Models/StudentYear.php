<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class StudentYear extends Model
{
    protected $primaryKey = 'student_year_id';
    use HasFactory;
    protected $fillable = [
        'student_year_id',
        'student_id',
        'year_id',
        'role_number',
        'learning_year',
    ];
    public function student()
    {
        return $this->belongsTo(User::class);
    }
    public function year()
    {
        return $this->belongsTo(Year::class, 'year_id')->with('courses');
    }
    public static function getStudentYearWithCourseCount()
    {
        $student_year = StudentYear::with('year')->where('student_id', Auth::user()->id)->orderBy('learning_year', 'desc')->first();
        if ($student_year) {
            $courses_count = Course::where('year_id', $student_year->year_id)->get()->count();
            $student_year->courses_count = $courses_count;
            return $student_year;
        } else {
            return null;
        }
    }

    public static function GetCurrentTeachingTeachers()
    {
        $current_year = StudentYear::where('learning_year', Carbon::now()->year)
            ->where('student_id', Auth::user()->id)
            ->latest()->first();
        $year = Year::with('courses')->where('id', $current_year->year_id)->first();
        $teachers = [];
        foreach ($year->courses as $course) {
            $teachers[] = TeacherCourse::with(['teacher', 'courses'])->where('course_id', $course->id)->where('teaching_year', Carbon::now()->year)->first();
        }
        return $teachers;
    }
}
