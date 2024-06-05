<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\FeedbackTemplate;
use App\Models\StudentYear;
use App\Models\TeacherCourse;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Sentiment\Analyzer;

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
        // foreach ($student_year->year->courses as $index => $course) {
        //     if ($course->semester == 1) {
        //     }
        // }
        foreach ($student_year->year->courses as $index => $course) {
            $teacher_course = TeacherCourse::where('course_id', $course->id)
                ->where('teaching_year', $student_year->learning_year)->first();
            if ($teacher_course) {
                $teacher = User::find($teacher_course->teacher_id);
                $course->teacher = $teacher;
                $feedbacks = Feedback::where('year_id', $student_year->year_id)
                    ->where('course_id', $course->id)
                    ->where('teacher_id', $course->teacher->id)
                    ->where('student_id', $student_year->student_id)
                    ->get();
            } else {
                $feedbacks = [];
            }
            // return $course;
            if (count($feedbacks) > 0) {
                unset($student_year->year->courses[$index]);
            } else if ($course->semester == 1 && !count($feedbacks) > 0) {
                foreach ($student_year->year->courses as $unset_index => $unset_course) {
                    if ($unset_course->semester === 2) {
                        unset($student_year->year->courses[$unset_index]);
                    }
                }
            }
        }

        $questions = explode(',', $feedback_template->feedback_template_question);

        // Check Teacher Exist
        if (!isset($student_year->year->courses[0]->teacher)) {
            // return $student_year;
            $student_year->year->courses = [];
        }
        return view('students.feedback.feedback_create', compact('questions', 'student_year', 'feedback_template'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'year_id'                                       => 'required|integer',
            'course_id'                                     => 'required|integer',
            'teacher_id'                                    => 'required|integer',
            'student_id'                                    => 'required|integer',
            'feedback_template_id'                          => 'required|integer',
            'feedback_strength_weakness'                    => 'nullable|string',
            'feedback_comment'                              => 'nullable|string',
            'learning_year'                                 => 'nullable|string',
            'learning_year_second_semester'                 => 'nullable|string',
        ]);
        $feedback_template = FeedbackTemplate::find($validated['feedback_template_id']);
        // return $validated;
        // Filter only the keys that match the pattern 'question_*'
        $filteredData = array_filter($request->all(), function ($key) {
            return preg_match('/^question_\d+$/', $key);
        }, ARRAY_FILTER_USE_KEY);
        // Extract the values, remove underscores, and join them into a single string
        $answers_array = array_values($filteredData);
        $answers = implode(',', $answers_array);

        $total = 0;
        $total_percentage = 0;
        $total_questions = count($answers_array);
        foreach ($answers_array as $answer_data) {
            if ($answer_data == 'very_good') $total += 100;
            if ($answer_data == 'good') $total += 80;
            if ($answer_data == 'normal') $total += 50;
            if ($answer_data == 'not_bad') $total += 30;
            if ($answer_data == 'bad') $total += 10;
        }
        $total_percentage = round($total / $total_questions, 2);

        $validated['feedback_questions'] = $feedback_template->feedback_template_question;
        $validated['feedback_answers'] = $answers;
        $validated['feedback_date'] = Carbon::now()->format('Y-m-d');
        $validated['feedback_total_percentage'] = $total_percentage;

        // Add ing Sentiment Analysis Algorithm
        $analyzer = new Analyzer();
        $feedback_strength_weakness = $analyzer->getSentiment($validated['feedback_strength_weakness']);
        $feedback_comment = $analyzer->getSentiment($validated['feedback_comment']);
        $feedback_strength_weakness_count = 1;
        $feedback_comment_count = 1;
        if ($feedback_strength_weakness['neu'] == 1 && $feedback_comment['neu'] == 1) {
            $feedback_strength_weakness_count = 0;
            $feedback_comment_count = 0;
        } elseif ($feedback_strength_weakness['neu'] == 1 || !isset($validated['feedback_strength_weakness'])) {
            $feedback_strength_weakness_count = 0;
        } elseif ($feedback_comment['neu'] == 1 || !isset($validated['feedback_comment'])) {
            $feedback_comment_count = 0;
        }
        $validated['feedback_total_percentage_comment'] =
            ($feedback_strength_weakness['compound'] + $feedback_comment['compound']) * 100 / ($feedback_strength_weakness_count + $feedback_comment_count);

        // return $validated['feedback_strength_weakness'];
        // return $validated;
        Feedback::create($validated);
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $feedback = Feedback::with('teacher', 'student', 'course', 'year')->find($id);
        if (!$feedback) {
            return redirect()->back();
        }
        $questions = explode(',', $feedback->feedback_questions);
        $answers = explode(',', $feedback->feedback_answers);
        $data_array = [];
        foreach ($questions as $key => $question) {
            $data_array[] = [
                'feedback_question' => $question,
                'feedback_answer' => $answers[$key],
            ];
        }
        // return $data_array;
        return view('students.feedback.feedback_detail', compact('feedback', 'data_array'));
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
    public function studentFeedback()
    {
        $feedbacks = Feedback::with('teacher', 'course', 'year')->where('student_id', Auth::user()->id)->orderBy('feedback_date', 'desc')->get();
        // return $feedbacks;
        return view('students.feedback.feedback_list', compact('feedbacks'));
    }
    public function teacherFeedbackList()
    {
        $feedbacks = Feedback::with('teacher', 'course', 'year')->where('teacher_id', Auth::user()->id)->orderBy('feedback_date', 'desc')->get();
        foreach ($feedbacks as $index => $feedback) {
            $questions = explode(',', $feedback->feedback_questions);
            $answers = explode(',', $feedback->feedback_answers);
            $data_array = [];
            foreach ($questions as $key => $question) {
                $data_array[] = [
                    'feedback_question' => $question,
                    'feedback_answer' => $answers[$key],
                ];
            }
            $feedbacks[$index]['data'] = $data_array;
        }
        // return $feedbacks;
        return view('teachers.feedback_list', compact('feedbacks'));
    }
    public function teacherFeedbackDetail(string $id)
    {
        $feedback = Feedback::with('teacher', 'student', 'course', 'year')->find($id);
        if (!$feedback) {
            return redirect()->back();
        }
        $questions = explode(',', $feedback->feedback_questions);
        $answers = explode(',', $feedback->feedback_answers);
        $data_array = [];
        foreach ($questions as $key => $question) {
            $data_array[] = [
                'feedback_question' => $question,
                'feedback_answer' => $answers[$key],
            ];
        }
        // return $data_array;
        return view('teachers.feedback_detail', compact('feedback', 'data_array'));
    }
}
