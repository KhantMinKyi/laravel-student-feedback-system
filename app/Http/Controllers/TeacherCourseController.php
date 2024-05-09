<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\TeacherCourse;
use App\Models\User;
use Illuminate\Http\Request;

class TeacherCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $teacher_courses = TeacherCourse::orderBy('teacher_id', 'asc')->get();
        $teachers = TeacherCourse::getTeachersWithCourses();
        // return $teachers;
        return view('admins.setting.teacher_course.teacher_course_list', compact('teachers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teachers = User::teachers();
        $courses = Course::with('year')->get();

        return view('admins.setting.teacher_course.teacher_course_create', compact('teachers', 'courses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|string',
            'teaching_course_ids' => 'required|array',
            'teaching_year' => 'required|string',
        ]);
        // return $validated['teaching_course_ids'];
        foreach ($validated['teaching_course_ids'] as $courses) {
            TeacherCourse::create([
                'teacher_id' => $validated['teacher_id'],
                'teaching_year' => $validated['teaching_year'],
                'course_id' => $courses,
            ]);
        }
        return redirect()->route('teacher_course.index');
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
        $teacher = User::find($id);
        $courses = Course::with('year')->get();
        $teacher_courses = TeacherCourse::getOneTeacherWithCourses($id);
        return view('admins.setting.teacher_course.teacher_course_edit', compact('teacher', 'courses', 'teacher_courses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $requestData = $request->all();
        $courses = [];

        // Loop through the request data to find course information
        foreach ($requestData as $key => $value) {
            // Check if the key starts with 'course_id_' and extract the course ID
            if (strpos($key, 'course_id_') === 0) {
                $courseId = substr($key, strlen('course_id_'));
                $teachingYear = $requestData['teaching_year_' . $courseId];
                $courses[$courseId] = [
                    'course_id' => $value,
                    'teaching_year' => $teachingYear
                ];
                unset($requestData['course_id_' . $courseId], $requestData['teaching_year_' . $courseId]);
            }
        }

        $requestData['courses'] = $courses;
        $teacherId = $requestData['teacher_id'];
        $courses = $requestData['courses'];
        foreach ($courses as $teacherCourseId => $course) {
            if (empty($course['course_id'])) {
                TeacherCourse::where('teacher_course_id', $teacherCourseId)->delete();
            } else {
                TeacherCourse::updateOrCreate(
                    ['teacher_course_id' => $teacherCourseId], // Update if exists, otherwise create new
                    [
                        'teacher_id' => $teacherId,
                        'course_id' => $course['course_id'],
                        'teaching_year' => $course['teaching_year']
                    ]
                );
            }
        }
        return redirect()->route('teacher_course.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        TeacherCourse::where('teacher_id', $id)->delete();
        return redirect()->route('teacher_course.index');
    }
}
