<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\student\CategoryController as StudentCategoryController;
use App\Http\Controllers\student\LessonController as StudentLessonController;
use App\Http\Controllers\tutor\LessonController as TutorLessonController;
use App\Http\Controllers\tutor\AppointmentController as TutorAppointmentController;
use App\Http\Controllers\tutor\BookingController as TutorBookingController;
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

/* auth */
Route::post('auth/login',[AuthController::class,'login']);
Route::post('auth/logout', [AuthController::class,'logout']);

// tutor
Route::group(['middleware' => ['api','auth.jwt','auth.tutor']], function(){
    // TODO: Methoden ergänzen
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/tutor/lessons', [TutorLessonController::class, 'index']);
    Route::get('/tutor/{lessonId}/appointments', [TutorAppointmentController::class, 'availableByLesson']);

    //Route::get('/tutor/appointments/booked', [TutorAppointmentController::class, 'booked']);
    //Route::get('/tutor/appointments/done', [TutorAppointmentController::class, 'done']);
    Route::get('/tutor/bookings', [TutorBookingController::class, 'index']);
    Route::get('/tutor/bookings/pending', [TutorBookingController::class, 'pending']);
    Route::get('/tutor/bookings/upcoming', [TutorBookingController::class, 'upcoming']);
    Route::get('/tutor/bookings/finished', [TutorBookingController::class, 'finished']);
});

// admin
Route::group(['middleware' => ['api','auth.jwt','auth.admin']], function(){

});

// general
Route::get('appointments', [AppointmentController::class, 'available']);
Route::get('/lessons', [LessonController::class, 'available']);
Route::get('categories/{id}', [CategoryController::class, 'findByID']);
Route::get('lessons/{id}', [LessonController::class, 'findByID']);
Route::get('appointments/{id}', [AppointmentController::class, 'findByID']);
