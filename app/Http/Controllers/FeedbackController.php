<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\FeedbackTemplate;
use App\Models\StudentYear;
use App\Models\TeacherCourse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $feedback_template = FeedbackTemplate::latest()->first();
        $student_year = StudentYear::getStudentYearWithCourseCount();

        foreach ($student_year->year->courses as $index => $course) {
            $teacher_course = TeacherCourse::where('course_id', $course->id)
                ->where('teaching_year', $student_year->learning_year)->first();
            if ($teacher_course) {
                $teacher = User::find($teacher_course->teacher_id);
                $course->teacher = $teacher;
            }
            $feedbacks = Feedback::where('year_id', $student_year->year_id)
                ->where('course_id', $course->id)
                ->where('teacher_id', $course->teacher->id)
                ->where('student_id', $student_year->student_id)
                ->get();
            if (count($feedbacks) > 0) {
                unset($student_year->year->courses[$index]);
            }
        }

        $questions = explode(',', $feedback_template->feedback_template_question);
        // return $student_year;
        return view('students.feedback.feedback_create', compact('questions', 'student_year'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return $request;
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
