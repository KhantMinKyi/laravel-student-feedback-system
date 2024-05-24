<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function studentDashboard()
    {
        $teacher_count = User::where('type', 'teacher')->get()->count();
        $student_count = User::where('type', 'student')->get()->count();

        return view('students.dashboard', compact(['teacher_count', 'student_count']));
    }
}
