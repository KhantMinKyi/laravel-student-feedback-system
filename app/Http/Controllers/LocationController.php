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

    public function studentProfile()
    {
        $user = User::with('student_year')->where('id', Auth::user()->id)->first();
        if (!$user) {
            return redirect()->back();
        }
        // return $user;
        return view('students.account.user_profile', compact('user'));
    }
    public function teacherProfile()
    {
        $user_id = Auth::user()->id;
        $user = User::where('id', $user_id)->first();
        if (!$user) {
            return redirect()->back();
        }
        $teaching_subjects = User::getOneTeacherWithCourses($user_id);
        // return $teaching_subjects;
        return view('teachers.account.user_profile', compact('user', 'teaching_subjects'));
    }
}
