<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\YearController;
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
    });
    Route::resource('year', YearController::class);
    Route::resource('course', CourseController::class);
});
Route::prefix('teacher')->middleware(['is_teacher'])->group(function () {
    Route::get('/', function () {
        return view('teachers.dashboard');
    });
});
Route::prefix('student')->middleware(['is_student'])->group(function () {
    Route::get('/', function () {
        return view('students.dashboard');
    });
});
Route::post('/text', [TestController::class, 'index']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);
