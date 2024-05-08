<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Year;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::orderBy('course_name', 'asc')->paginate(10);
        return view('admins.setting.course.course_list', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $years = Year::all();
        return view('admins.setting.course.course_create', compact('years'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_name' => 'required|string',
            'year_id' => 'required|numeric',
        ]);
        Course::create($validated);
        return redirect()->route('course.index');
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
        $course = Course::find($id);
        if (!$course) {
            return redirect()->back();
        }
        $years = Year::all();
        return view('admins.setting.course.course_edit', compact('course', 'years'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'course_name' => 'required|string',
            'year_id' => 'required|numeric'
        ]);
        $course = Course::find($id);
        if (!$course) {
            return redirect()->back();
        }
        $course->update($validated);
        return redirect()->route('course.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Course::find($id)->delete();
        return redirect()->route('course.index');
    }
}
