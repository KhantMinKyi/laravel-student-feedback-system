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
        'learning_year_second_semester',
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
        $student_year = StudentYear::with('year')->where('student_id', Auth::user()->id)->orderBy('learning_year_second_semester', 'desc')->first();
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
        $current_year = StudentYear::where('student_id', Auth::user()->id)
            // ->where('learning_year', Carbon::now()->year)
            ->orderBy('learning_year_second_semester', 'desc')
            ->latest()->first();
        $year = Year::with('courses')->where('id', $current_year->year_id)->first();
        // return $year;
        $teachers = [];
        foreach ($year->courses as $course) {
            $teachers[] = TeacherCourse::with(['teacher', 'courses'])->where('course_id', $course->id)
                // ->where('teaching_year', Carbon::now()->year)
                ->first();
        }
        return $teachers;
    }
}
