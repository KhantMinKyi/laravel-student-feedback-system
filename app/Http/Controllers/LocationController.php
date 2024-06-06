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
        // add Data format with year
        $data_array = [];
        foreach ($feedbacks as $index => $feedback) {
            $feedback_data_array = [];
            $feedback_data_array['name'] = $index;
            $feedback_data_array['data'] = [];
            foreach ($feedback as $data) {
                array_push($feedback_data_array['data'], [
                    'course_id' => $data->course->id,
                    'course_name' => $data->course->course_name,
                    'feedback_total_percentage' => $data->feedback_total_percentage,
                    'feedback_total_percentage_comment' => $data->feedback_total_percentage_comment,
                ]);
            }
            array_push($data_array, $feedback_data_array);
        }

        // calculate the count and total for array
        $yearlyData = [];
        foreach ($data_array as $year) {
            $sums = [];
            $counts = [];
            $comment_sums = [];
            foreach ($year['data'] as $item) {
                if (isset($sums[$item['course_id']])) {
                    $sums[$item['course_id']] += $item['feedback_total_percentage'];
                    $comment_sums[$item['course_id']] += $item['feedback_total_percentage_comment'];
                    $counts[$item['course_id']] += 1;
                } else {
                    $sums[$item['course_id']] = $item['feedback_total_percentage'];
                    $comment_sums[$item['course_id']] = $item['feedback_total_percentage_comment'];
                    $counts[$item['course_id']] = 1;
                }
            }

            $yearResult = [
                "name" => $year["name"],
                "data" => []
            ];
            // calculate the average and arrange the data
            foreach ($sums as $course_id => $total_feedback_percentage) {
                $average_feedback_percentage = $total_feedback_percentage / $counts[$course_id];
                $average_feedback_percentage_comment = $comment_sums[$course_id] / $counts[$course_id];
                $course = Course::find($course_id);
                $yearResult["data"][] = [
                    'course_id' => $course_id,
                    'course_name' => $course ? $course->course_name . ' ( Semester - ' . $course->semester . ')'  : 'Unknown',
                    'total_feedback_percentage' => $total_feedback_percentage,
                    'average_feedback_percentage' => $average_feedback_percentage,
                    'average_feedback_percentage_comment' => $average_feedback_percentage_comment,
                ];
            }

            $yearlyData[] = $yearResult;
        }
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
