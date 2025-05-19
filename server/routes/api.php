<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\student\CategoryController as StudentCategoryController;
use App\Http\Controllers\student\LessonController as StudentLessonController;
use App\Http\Controllers\tutor\LessonController as TutorLessonController;
use App\Http\Controllers\tutor\AppointmentController as TutorAppointmentController;
use App\Http\Controllers\tutor\BookingController as TutorBookingController;
use App\Http\Controllers\tutor\CategoryController as TutorCategoryController;
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
    Route::get('/tutor/categories', [TutorCategoryController::class, 'index']);

    Route::get('/tutor/lessons', [TutorLessonController::class, 'index']);
    Route::post('/tutor/lessons', [TutorLessonController::class, 'store']);
    Route::put('/tutor/lessons/{lessonId}', [TutorLessonController::class, 'update']);
    Route::delete('/tutor/lessons/{lessonId}', [TutorLessonController::class, 'delete']);

    Route::get('/tutor/{lessonId}/appointments', [TutorAppointmentController::class, 'availableByLesson']);
    Route::post('/tutor/appointments', [TutorAppointmentController::class, 'store']);
    Route::put('/tutor/appointments/{appointmentId}', [TutorAppointmentController::class, 'update']);
    Route::delete('/tutor/appointments/{appointmentId}', [TutorAppointmentController::class, 'delete']);

    Route::get('/tutor/bookings', [TutorBookingController::class, 'index']);
    Route::get('/tutor/bookings/pending', [TutorBookingController::class, 'pending']);
    Route::get('/tutor/bookings/upcoming', [TutorBookingController::class, 'upcoming']);
    Route::get('/tutor/bookings/finished', [TutorBookingController::class, 'finished']);
    Route::put('/tutor/bookings/{bookingId}/accept', [TutorBookingController::class, 'accept']);
    Route::put('/tutor/bookings/{bookingId}/reject', [TutorBookingController::class, 'reject']);
});

// admin
Route::group(['middleware' => ['api','auth.jwt','auth.admin']], function(){
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{categoryId}', [CategoryController::class, 'update']);
    Route::delete('/categories/delete/{categoryId}', [CategoryController::class, 'delete']);
});

// student
Route::group(['middleware' => ['api','auth.jwt','auth.student']], function(){
    Route::post('/bookings', [BookingController::class, 'store']);
});


// general
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/appointments', [AppointmentController::class, 'available']);
Route::get('/categories/lessons/{id}', [LessonController::class, 'availableByID']);
Route::get('/categories/{id}', [CategoryController::class, 'findByID']);
Route::get('/lessons/{id}', [LessonController::class, 'findByID']);
Route::get('/appointments/{id}', [AppointmentController::class, 'findByID']);


