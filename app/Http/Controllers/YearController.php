<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Feedback;
use App\Models\TeacherCourse;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class YearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $years = Year::orderBy('year_name', 'asc')->get();
        return view('admins.setting.year.year_list', compact('years'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admins.setting.year.year_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'year_name' => 'required|string',
            // 'semester' => 'required|numeric',
        ]);
        Year::create($validated);
        return redirect()->route('year.index');
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
        $year = Year::find($id);
        if (!$year) {
            return redirect()->back();
        }
        return view('admins.setting.year.year_edit', compact('year'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'year_name' => 'required|string',
            // 'semester' => 'required|numeric'
        ]);
        $year = Year::find($id);
        if (!$year) {
            return redirect()->back();
        }
        $year->update($validated);
        return redirect()->route('year.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $year = Year::find($id);
        $courses = Course::where('year_id', $year->id)->get();
        foreach ($courses as $course) {
            TeacherCourse::where('course_id', $course->id)->delete();
        }
        $year->delete();
        return redirect()->route('year.index');
    }
}
