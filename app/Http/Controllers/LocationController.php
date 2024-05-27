<?php

namespace App\Http\Controllers;

use App\Models\StudentYear;
use App\Models\TeacherCourse;
use App\Models\User;
use App\Models\Year;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    public function studentDashboard()
    {
        $teacher_count = User::where('type', 'teacher')->get()->count();
        $student_count = User::where('type', 'student')->get()->count();
        $current_learning_courses = StudentYear::GetCurrentTeachingTeachers();
        // return $current_learning_courses;
        return view('students.dashboard', compact(['teacher_count', 'student_count', 'current_learning_courses']));
    }
}
