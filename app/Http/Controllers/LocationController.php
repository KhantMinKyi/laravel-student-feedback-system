<?php

namespace App\Http\Controllers;

use App\Charts\TeacherCourseChart;
use App\Models\Course;
use App\Models\Feedback;
use App\Models\StudentYear;
use App\Models\TeacherCourse;
use App\Models\User;
use App\Models\Year;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    private function getTeacherPersonalChart($feedbacks)
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
                    $strongly_agree_point = $courseData['strongly_agree_point'] / $courseData['count'];
                    $average_feedback_percentage = $courseData['feedback_total_percentage'] / $courseData['count'];
                    $average_feedback_percentage_comment = $courseData['feedback_total_percentage_comment'] / $courseData['count'];
                    $course = Course::find($course_id);
                    $yearResult["data"][$year_id][] = [
                        'year_id'                                                               => $year_id,
                        'course_id'                                                             => $course_id,
                        'course_name'                                                           => $course ? $course->course_name . ' ( Semester - ' . $course->semester . ')' : 'Unknown',
                        'strongly_agree_point'                                                  => $strongly_agree_point,
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
        // dd($yearlyData);
        return $yearlyData;
    }
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
    public function studentDashboard()
    {
        $teacher_count = User::where('type', 'teacher')->get()->count();
        $student_count = User::where('type', 'student')->get()->count();
        $current_learning_courses = StudentYear::GetCurrentTeachingTeachers();
        $current_year = StudentYear::where('student_id', Auth::user()->id)
            // ->where('learning_year', Carbon::now()->year)
            ->orderBy('learning_year_second_semester', 'desc')
            ->latest()->first();
        // return $current_learning_courses;
        return view('students.dashboard', compact(['teacher_count', 'student_count', 'current_learning_courses', 'current_year']));
    }
    public function teacherDashboard()
    {
        $teacher_count = User::where('type', 'teacher')->get()->count();
        $student_count = User::where('type', 'student')->get()->count();
        $teacher = User::find(Auth::user()->id);
        $teacher_courses = TeacherCourse::getOneTeacherWithCourses($teacher->id);
        // return $teacher_courses;
        $feedbacks = Feedback::with('course')->where('teacher_id', $teacher->id)->get()->groupBy(
            function ($feedback) {
                return $feedback->learning_year . ' - ' . $feedback->learning_year_second_semester;
            }
        );
        $yearlyData = $this->getTeacherPersonalChart($feedbacks);
        // return $yearlyData;
        return view('teachers.dashboard', compact(['teacher', 'teacher_count', 'student_count', 'yearlyData', 'teacher_courses']));
    }
    public function adminDashboard()
    {
        $teacher_count = User::where('type', 'teacher')->get()->count();
        $student_count = User::where('type', 'student')->get()->count();
        $teachers = TeacherCourse::getTeachersWithCourses();
        $feedbacks = Feedback::with('course', 'year')->get()->groupBy(
            function ($feedback) {
                return $feedback->learning_year . ' - ' . $feedback->learning_year_second_semester;
            }
        );
        $yearlyData = $this->getAllTeachersPersonalChart($feedbacks);
        return view('admins.dashboard', compact(['teacher_count', 'student_count', 'yearlyData', 'teachers']));
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
