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
                ]);
            }
            array_push($data_array, $feedback_data_array);
        }

        // calculate the count and total for array
        $yearlyData = [];
        foreach ($data_array as $year) {
            $sums = [];
            $counts = [];

            foreach ($year['data'] as $item) {
                if (isset($sums[$item['course_id']])) {
                    $sums[$item['course_id']] += $item['feedback_total_percentage'];
                    $counts[$item['course_id']] += 1;
                } else {
                    $sums[$item['course_id']] = $item['feedback_total_percentage'];
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
                $course = Course::find($course_id);
                $yearResult["data"][] = [
                    'course_id' => $course_id,
                    'course_name' => $course ? $course->course_name : 'Unknown',
                    'total_feedback_percentage' => $total_feedback_percentage,
                    'average_feedback_percentage' => $average_feedback_percentage
                ];
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
        return view('teachers.dashboard', compact(['teacher', 'teacher_count', 'student_count', 'yearlyData', 'teacher_courses']));
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
