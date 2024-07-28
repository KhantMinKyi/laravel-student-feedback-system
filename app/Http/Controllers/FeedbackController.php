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
                $answers = explode(',', $data->feedback_questions);
                array_push($feedback_data_array['data'], [
                    'course_id'                             => $data->course->id,
                    'year_id'                               => $data->year->year_name,
                    'course_name'                           => $data->course->course_name,
                    'strongly_agree_point'                  => $data->strongly_agree_point,
                    'agree_point'                           => $data->agree_point,
                    'neutral_point'                         => $data->neutral_point,
                    'disagree_point'                        => $data->disagree_point,
                    'strongly_disagree_point'               => $data->strongly_disagree_point,
                    'feedback_total_point'                  => $data->feedback_total_point,
                    'feedback_total_percentage'             => $data->feedback_total_percentage,
                    'feedback_total_percentage_comment'     => $data->feedback_total_percentage_comment,
                    'feedback_questions'                    => count($answers),
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
                    $groupedData[$year_id][$course_id]['strongly_agree_point']                  += $item['strongly_agree_point'];
                    $groupedData[$year_id][$course_id]['agree_point']                           += $item['agree_point'];
                    $groupedData[$year_id][$course_id]['neutral_point']                         += $item['neutral_point'];
                    $groupedData[$year_id][$course_id]['disagree_point']                        += $item['disagree_point'];
                    $groupedData[$year_id][$course_id]['strongly_disagree_point']               += $item['strongly_disagree_point'];
                    $groupedData[$year_id][$course_id]['feedback_total_point']                  += $item['feedback_total_point'];
                    $groupedData[$year_id][$course_id]['feedback_total_percentage']             += $item['feedback_total_percentage'];
                    $groupedData[$year_id][$course_id]['feedback_total_percentage_comment']     += $item['feedback_total_percentage_comment'];
                    $groupedData[$year_id][$course_id]['feedback_questions']                     = $item['feedback_questions'];
                    $groupedData[$year_id][$course_id]['count']                                 += 1;
                } else {
                    $groupedData[$year_id][$course_id] = [
                        'course_id'                                 => $course_id,
                        'course_name'                               => $item['course_name'],
                        'strongly_agree_point'                      => $item['strongly_agree_point'],
                        'agree_point'                               => $item['agree_point'],
                        'neutral_point'                             => $item['neutral_point'],
                        'disagree_point'                            => $item['disagree_point'],
                        'strongly_disagree_point'                   => $item['strongly_disagree_point'],
                        'feedback_total_point'                      => $item['feedback_total_point'],
                        'feedback_total_percentage'                 => $item['feedback_total_percentage'],
                        'feedback_total_percentage_comment'         => $item['feedback_total_percentage_comment'],
                        'feedback_questions'                        => $item['feedback_questions'],
                        'count'                                     => 1
                    ];
                }
            }

            $yearResult = [
                "name" => $year["name"],
                "data" => []
            ];

            foreach ($groupedData as $year_id => $courses) {
                foreach ($courses as $course_id => $courseData) {
                    $total =           $courseData['strongly_agree_point'] +
                        $courseData['agree_point'] +
                        $courseData['neutral_point'] +
                        $courseData['disagree_point'] +
                        $courseData['strongly_disagree_point'];
                    $average_strongly_agree_point_percentage = round(($courseData['strongly_agree_point'] * 100) / $total, 2);
                    $average_agree_point_percentage = round(($courseData['agree_point'] * 100) / $total, 2);
                    $average_neutral_point_percentage = round(($courseData['neutral_point'] * 100) / $total, 2);
                    $average_disagree_point_percentage = round(($courseData['disagree_point'] * 100) / $total, 2);
                    $average_strongly_disagree_point_percentage = round(($courseData['strongly_disagree_point'] * 100) / $total, 2);
                    $strongly_agree_point = $courseData['strongly_agree_point'];
                    $average_feedback_percentage = $courseData['feedback_total_percentage'] / $courseData['count'];
                    $average_feedback_percentage_comment = $courseData['feedback_total_percentage_comment'] / $courseData['count'];
                    $course = Course::find($course_id);
                    $yearResult["data"][$year_id][] = [
                        'year_id'                                                               => $year_id,
                        'course_id'                                                             => $course_id,
                        'course_name'                                                           => $course ? $course->course_name . ' ( Semester - ' . $course->semester . ')' : 'Unknown',
                        'strongly_agree_point'                                                  => $strongly_agree_point,
                        'agree_point'                                                           => $courseData['agree_point'],
                        'neutral_point'                                                         => $courseData['neutral_point'],
                        'disagree_point'                                                        => $courseData['disagree_point'],
                        'strongly_disagree_point'                                               => $courseData['strongly_disagree_point'],
                        'average_strongly_agree_point_percentage'                               => $average_strongly_agree_point_percentage,
                        'average_agree_point_percentage'                                        => $average_agree_point_percentage,
                        'average_neutral_point_percentage'                                      => $average_neutral_point_percentage,
                        'average_disagree_point_percentage'                                     => $average_disagree_point_percentage,
                        'average_strongly_disagree_point_percentage'                            => $average_strongly_disagree_point_percentage,
                        'total_feedback_percentage'                                             => $courseData['feedback_total_percentage'],
                        'average_feedback_percentage'                                           => $average_feedback_percentage,
                        'average_feedback_percentage_comment'                                   => $average_feedback_percentage_comment,
                    ];
                }
            }

            $yearlyData[] = $yearResult;
            // dd($yearlyData);
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
        $validated['strongly_agree_point'] = 0;
        $validated['agree_point'] = 0;
        $validated['neutral_point'] = 0;
        $validated['disagree_point'] = 0;
        $validated['strongly_disagree_point'] = 0;
        $total_percentage = 0;
        $total_questions = count($answers_array);
        foreach ($answers_array as $answer_data) {
            if ($answer_data == 'very_good') {
                $total += 5;
                $validated['strongly_agree_point'] += 5;
            }
            if ($answer_data == 'good') {
                $total += 4;
                $validated['agree_point'] += 4;
            }
            if ($answer_data == 'normal') {
                $total += 3;
                $validated['neutral_point'] += 3;
            }
            if ($answer_data == 'not_bad') {
                $total += 2;
                $validated['disagree_point'] += 2;
            }
            if ($answer_data == 'bad') {
                $total += 1;
                $validated['strongly_disagree_point'] += 1;
            }
        }
        $validated['feedback_total_point'] = $total;
        // $total_percentage = round($total / $total_questions, 2);
        $total_percentage = round(($total * 100) / ($total_questions * 5), 2);
        $validated['feedback_questions'] = $feedback_template->feedback_template_question;
        $validated['feedback_answers'] = $answers;
        $validated['feedback_date'] = Carbon::now()->format('Y-m-d');
        $validated['feedback_total_percentage'] = $total_percentage;
        // return $validated;

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

        return view('teachers.feedback_list_all_teacher', compact('yearlyData'));
    }
}
