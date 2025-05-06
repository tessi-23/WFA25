<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\student\CategoryController as StudentCategoryController;
use App\Http\Controllers\student\LessonController as StudentLessonController;
use App\Http\Controllers\student\AppointmentController as StudentAppointmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/lessons', [LessonController::class, 'index']);
Route::get('/student/appointments', [StudentAppointmentController::class, 'index']);

Route::get('categories/{id}', [CategoryController::class, 'findByID']);
Route::get('lessons/{id}', [LessonController::class, 'findByID']);
Route::get('appointments/{id}', [AppointmentController::class, 'findByID']);
