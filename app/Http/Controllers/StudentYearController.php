<?php

namespace App\Http\Controllers;

use App\Models\StudentYear;
use App\Models\User;
use App\Models\Year;
use Illuminate\Http\Request;

class StudentYearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $student_years = StudentYear::with('student', 'year')->get();
        return view('admins.setting.student_year.student_year_list', compact('student_years'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = User::where('type', 'student')->get();
        $years = Year::all();
        return view('admins.setting.student_year.student_year_create', compact('students', 'years'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|numeric',
            'year_id' => 'required|numeric',
            'role_number' => 'required|string',
            'learning_year' => 'required|string',
        ]);
        StudentYear::create($validated);
        return redirect()->route('student_year.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $student_year = StudentYear::find($id);
        if (!$student_year) {
            return redirect()->back();
        }
        $students = User::where('type', 'student')->get();
        $years = Year::all();
        return view('admins.setting.student_year.student_year_edit', compact(['student_year', 'students', 'years']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $student_year = StudentYear::find($id);
        if (!$student_year) {
            return redirect()->back();
        }
        $validated = $request->validate([
            'student_id' => 'required|numeric',
            'year_id' => 'required|numeric',
            'role_number' => 'required|string',
            'learning_year' => 'required|string',
        ]);
        $student_year->update($validated);
        return redirect()->route('student_year.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student_year = StudentYear::find($id);
        if (!$student_year) {
            return redirect()->back();
        }
        $student_year->delete();
    }
}
