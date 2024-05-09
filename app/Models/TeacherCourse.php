<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherCourse extends Model
{
    protected $primaryKey = 'teacher_course_id';
    use HasFactory;
    protected $fillable = [
        'course_id',
        'teacher_id',
        'teaching_year',
    ];
    public function teacher()
    {
        return $this->belongsTo(User::class);
    }
    public function courses()
    {
        return $this->belongsTo(Course::class, 'course_id')->with(['year']);;
    }
    public static function getTeachersWithCourses()
    {
        $teachers = User::where('type', 'teacher')->orderBy('id', 'asc')->get();
        $data = [];

        foreach ($teachers as $teacher) {
            $teacher_courses = TeacherCourse::with('courses')->where('teacher_id', $teacher->id)->get();
            if (count($teacher_courses) > 0) {
                $data[] = [
                    'teacher' => $teacher,
                    'teacher_courses' => $teacher_courses,
                ];
            }
        }

        return $data;
    }
    public static function getOneTeacherWithCourses($id)
    {
        $teacher = User::find($id);
        $data = new \stdClass;

        $teacher_courses = TeacherCourse::with('courses')->where('teacher_id', $teacher->id)->get();
        if (count($teacher_courses) > 0) {
            $data->teacher = $teacher;
            $data->teacher_courses = $teacher_courses;
        }

        return $data;
    }
}
