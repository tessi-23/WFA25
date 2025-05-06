<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    function index(): JsonResponse {
        $appointments = Appointment::all();
        return response()->json($appointments, 200);
    }
}
