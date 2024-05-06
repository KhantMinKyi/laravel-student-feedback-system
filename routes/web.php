<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TestController;
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
Route::post('/login', [AuthController::class, 'login']);
Route::prefix('admin')->middleware(['is_admin'])->group(function () {
    Route::get('/', function () {
        return view('admins.dashboard');
    });
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
Route::get('/logout', [AuthController::class, 'logout']);
