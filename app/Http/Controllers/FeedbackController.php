<?php

namespace App\Http\Controllers;

use App\Models\Course;
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
    private function getAllTeachersPersonalChart($feedbacks)
    {
        // Initialize data array
        $data_array = [];
        foreach ($feedbacks as $index => $feedback) {
            $feedback_data_array = [];
            $feedback_data_array['name'] = $index;
            $feedback_data_array['data'] = [];
            foreach ($feedback as $data) {
                array_push($feedback_data_array['data'], [
                    'course_id' => $data->course->id,
                    'year_id' => $data->year->year_name,
                    'course_name' => $data->course->course_name,
                    'feedback_total_percentage' => $data->feedback_total_percentage,
                    'feedback_total_percentage_comment' => $data->feedback_total_percentage_comment,
                ]);
            }
            array_push($data_array, $feedback_data_array);
        }

        // Calculate the count and total for array
        $yearlyData = [];
        foreach ($data_array as $year) {
            $groupedData = [];

            foreach ($year['data'] as $item) {
                $year_id = $item['year_id'];
                $course_id = $item['course_id'];

                if (!isset($groupedData[$year_id])) {
                    $groupedData[$year_id] = [];
                }

                if (isset($groupedData[$year_id][$course_id])) {
                    $groupedData[$year_id][$course_id]['feedback_total_percentage'] += $item['feedback_total_percentage'];
                    $groupedData[$year_id][$course_id]['feedback_total_percentage_comment'] += $item['feedback_total_percentage_comment'];
                    $groupedData[$year_id][$course_id]['count'] += 1;
                } else {
                    $groupedData[$year_id][$course_id] = [
                        'course_id' => $course_id,
                        'course_name' => $item['course_name'],
                        'feedback_total_percentage' => $item['feedback_total_percentage'],
                        'feedback_total_percentage_comment' => $item['feedback_total_percentage_comment'],
                        'count' => 1
                    ];
                }
            }

            $yearResult = [
                "name" => $year["name"],
                "data" => []
            ];

            foreach ($groupedData as $year_id => $courses) {
                foreach ($courses as $course_id => $courseData) {
                    $average_feedback_percentage = $courseData['feedback_total_percentage'] / $courseData['count'];
                    $average_feedback_percentage_comment = $courseData['feedback_total_percentage_comment'] / $courseData['count'];
                    $course = Course::find($course_id);
                    $yearResult["data"][$year_id][] = [
                        'year_id' => $year_id,
                        'course_id' => $course_id,
                        'course_name' => $course ? $course->course_name . ' ( Semester - ' . $course->semester . ')' : 'Unknown',
                        'total_feedback_percentage' => $courseData['feedback_total_percentage'],
                        'average_feedback_percentage' => $average_feedback_percentage,
                        'average_feedback_percentage_comment' => $average_feedback_percentage_comment,
                    ];
                }
            }

            $yearlyData[] = $yearResult;
        }

        return $yearlyData;
    }




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
        // return response()->json($student_year);
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

        // return response()->json($student_year->year->courses);
        // Check Teacher Exist
        foreach ($student_year->year->courses as $array_key => $course_teacher) {
            if (!isset($course_teacher->teacher)) {
                unset($student_year->year->courses[$array_key]);
            }
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
        $validated['feedback_strength_weakness_neu'] = $feedback_strength_weakness['neu'];
        $validated['feedback_strength_weakness_pos'] = $feedback_strength_weakness['pos'];
        $validated['feedback_strength_weakness_neg'] = $feedback_strength_weakness['neg'];
        $validated['feedback_strength_weakness_compound'] = $feedback_strength_weakness['compound'];
        $validated['feedback_comment_neu'] = $feedback_comment['neu'];
        $validated['feedback_comment_pos'] = $feedback_comment['pos'];
        $validated['feedback_comment_neg'] = $feedback_comment['neg'];
        $validated['feedback_comment_compound'] = $feedback_comment['compound'];
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
    public function adminAllTeacherFeedbackList()
    {
        $feedbacks = Feedback::with('teacher', 'course', 'year')->orderBy('feedback_date', 'desc')->get();
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
        return view('admins.feedback.feedback_list', compact('feedbacks'));
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
    public function adminTeacherFeedbackDetail(string $id)
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
        return view('admins.feedback.feedback_detail', compact('feedback', 'data_array'));
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

    public function allTeacherFeedbackList()
    {
        $teacher_count = User::where('type', 'teacher')->get()->count();
        $student_count = User::where('type', 'student')->get()->count();
        $teacher = User::find(Auth::user()->id);
        $teacher_courses = TeacherCourse::getOneTeacherWithCourses($teacher->id);
        // return $teacher_courses;
        $feedbacks = Feedback::with('course', 'year')->get()->groupBy(
            function ($feedback) {
                return $feedback->learning_year . ' - ' . $feedback->learning_year_second_semester;
            }
        );
        $yearlyData = $this->getAllTeachersPersonalChart($feedbacks);
        // return $yearlyData;


        // $yearlyData = [
        //     [
        //         "name" => "2023 - 2024",
        //         "data" => [
        //             "Fifth Year" => [
        //                 [
        //                     "year_id" => "Fifth Year",
        //                     "course_id" => 1,
        //                     "course_name" => "DSP ( Semester - 1)",
        //                     "total_feedback_percentage" => 100,
        //                     "average_feedback_percentage" => 100,
        //                     "average_feedback_percentage_comment" => 53.87
        //                 ],
        //                 [
        //                     "year_id" => "Fifth Year",
        //                     "course_id" => 2,
        //                     "course_name" => "DSP ( Semester - 2)",
        //                     "total_feedback_percentage" => 80,
        //                     "average_feedback_percentage" => 80,
        //                     "average_feedback_percentage_comment" => 45.8
        //                 ],
        //                 [
        //                     "year_id" => "Fifth Year",
        //                     "course_id" => 1,
        //                     "course_name" => "DSP ( Semester - 1)",
        //                     "total_feedback_percentage" => 100,
        //                     "average_feedback_percentage" => 100,
        //                     "average_feedback_percentage_comment" => 53.87
        //                 ],
        //                 [
        //                     "year_id" => "Fifth Year",
        //                     "course_id" => 2,
        //                     "course_name" => "DSP ( Semester - 2)",
        //                     "total_feedback_percentage" => 80,
        //                     "average_feedback_percentage" => 80,
        //                     "average_feedback_percentage_comment" => 45.8
        //                 ],
        //                 [
        //                     "year_id" => "Fifth Year",
        //                     "course_id" => 1,
        //                     "course_name" => "DSP ( Semester - 1)",
        //                     "total_feedback_percentage" => 100,
        //                     "average_feedback_percentage" => 100,
        //                     "average_feedback_percentage_comment" => 53.87
        //                 ],
        //                 [
        //                     "year_id" => "Fifth Year",
        //                     "course_id" => 2,
        //                     "course_name" => "DSP ( Semester - 2)",
        //                     "total_feedback_percentage" => 80,
        //                     "average_feedback_percentage" => 80,
        //                     "average_feedback_percentage_comment" => 45.8
        //                 ],
        //                 [
        //                     "year_id" => "Fifth Year",
        //                     "course_id" => 3,
        //                     "course_name" => "HSS ( Semester - 1)",
        //                     "total_feedback_percentage" => 80,
        //                     "average_feedback_percentage" => 80,
        //                     "average_feedback_percentage_comment" => 45.8
        //                 ]
        //             ],
        //             "Sixth Year" => [
        //                 [
        //                     "year_id" => "Sixth Year",
        //                     "course_id" => 4,
        //                     "course_name" => "AI ( Semester - 2)",
        //                     "total_feedback_percentage" => 100,
        //                     "average_feedback_percentage" => 100,
        //                     "average_feedback_percentage_comment" => 68.8
        //                 ]
        //             ]
        //         ]
        //     ],
        //     [
        //         "name" => "2022 - 2023",
        //         "data" => [
        //             "Fifth Year" => [
        //                 [
        //                     "year_id" => "Fifth Year",
        //                     "course_id" => 1,
        //                     "course_name" => "DSP ( Semester - 1)",
        //                     "total_feedback_percentage" => 80,
        //                     "average_feedback_percentage" => 80,
        //                     "average_feedback_percentage_comment" => 45.8
        //                 ]
        //             ]
        //         ]
        //     ],
        //     [
        //         "name" => "2021 - 2022",
        //         "data" => [
        //             "First Year" => [
        //                 [
        //                     "year_id" => "First Year",
        //                     "course_id" => 1,
        //                     "course_name" => "Mathematics",
        //                     "total_feedback_percentage" => 90,
        //                     "average_feedback_percentage" => 90,
        //                     "average_feedback_percentage_comment" => 67.2
        //                 ],
        //                 [
        //                     "year_id" => "First Year",
        //                     "course_id" => 2,
        //                     "course_name" => "Physics",
        //                     "total_feedback_percentage" => 85,
        //                     "average_feedback_percentage" => 85,
        //                     "average_feedback_percentage_comment" => 72.1
        //                 ],
        //                 [
        //                     "year_id" => "First Year",
        //                     "course_id" => 3,
        //                     "course_name" => "Chemistry",
        //                     "total_feedback_percentage" => 88,
        //                     "average_feedback_percentage" => 88,
        //                     "average_feedback_percentage_comment" => 68.9
        //                 ]
        //             ],
        //             "Second Year" => [
        //                 [
        //                     "year_id" => "Second Year",
        //                     "course_id" => 4,
        //                     "course_name" => "Biology",
        //                     "total_feedback_percentage" => 82,
        //                     "average_feedback_percentage" => 82,
        //                     "average_feedback_percentage_comment" => 70.5
        //                 ]
        //             ],
        //             "Third Year" => [
        //                 [
        //                     "year_id" => "Third Year",
        //                     "course_id" => 5,
        //                     "course_name" => "Computer Science",
        //                     "total_feedback_percentage" => 95,
        //                     "average_feedback_percentage" => 95,
        //                     "average_feedback_percentage_comment" => 85.3
        //                 ]
        //             ],
        //             "Fourth Year" => [
        //                 [
        //                     "year_id" => "Fourth Year",
        //                     "course_id" => 6,
        //                     "course_name" => "Electrical Engineering",
        //                     "total_feedback_percentage" => 87,
        //                     "average_feedback_percentage" => 87,
        //                     "average_feedback_percentage_comment" => 75.6
        //                 ]
        //             ]
        //         ]
        //     ]
        // ];


        return view('teachers.feedback_list_all_teacher', compact('yearlyData'));
    }
}
