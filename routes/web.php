<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\FeedbackTemplateController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\StudentYearController;
use App\Http\Controllers\TeacherCourseController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\YearController;
use App\Models\TeacherCourse;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('login');
});
Route::prefix('admin')->middleware(['is_admin'])->group(function () {
    Route::get('/', function () {
        return view('admins.dashboard');
    })->name('admin.index');
    Route::resource('year', YearController::class);
    Route::resource('course', CourseController::class);
    Route::resource('user', UserController::class);
    Route::resource('teacher_course', TeacherCourseController::class);
    Route::resource('student_year', StudentYearController::class);
    Route::resource('feedback_template', FeedbackTemplateController::class);
    Route::get('/teacher_list', [UserController::class, 'teacherList'])->name('teacher.list');
    Route::get('/student_list', [UserController::class, 'studentList'])->name('student.list');
});

// Teacher
Route::prefix('teacher')->middleware(['is_teacher'])->group(function () {
    Route::get('/', [LocationController::class, 'teacherDashboard'])->name('teacher.index');
    Route::get('/teacher_profile', [LocationController::class, 'teacherProfile'])->name('teacher.profile');
    Route::get('/teacher_list', [UserController::class, 'teacherList'])->name('teacher.teacher.index');
    Route::get('/student_list', [UserController::class, 'studentList'])->name('teacher.student.index');
    Route::get('/feedback_list', [FeedbackController::class, 'teacherFeedbackList'])->name('teacher.feedback.index');
    Route::get('/feedback_list/{id}', [FeedbackController::class, 'teacherFeedbackDetail'])->name('teacher.feedback.detail');
});

// Student
Route::prefix('student')->middleware(['is_student'])->group(function () {
    Route::get('/', [LocationController::class, 'studentDashboard'])->name('student.index');
    Route::get('/student_profile', [LocationController::class, 'studentProfile'])->name('student.profile');
    Route::get('/teacher_list', [UserController::class, 'teacherList'])->name('student.teacher.index');
    Route::resource('feedback', FeedbackController::class);
    Route::get('/feedback_list', [FeedbackController::class, 'studentFeedback'])->name('student.feedback.index');
});
Route::get('/test', [TestController::class, 'index']);
Route::post('/test', [TestController::class, 'post']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);
