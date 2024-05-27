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
        $current_year = StudentYear::where('learning_year', Carbon::now()->year)
            ->where('student_id', Auth::user()->id)
            ->latest()->first();
        // return $current_year;
        return view('students.dashboard', compact(['teacher_count', 'student_count', 'current_learning_courses', 'current_year']));
    }
}
