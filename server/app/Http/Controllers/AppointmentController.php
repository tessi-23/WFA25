<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function findByID(string $id): JsonResponse {
        $appointment = Appointment::where('id', $id)->first();
        return $appointment != null ? response()->
        json($appointment, 200) : response()->
        json(null, 200);
    }

    public function available(): JsonResponse {
        $appointments = Appointment::where('status', 'available')->get();
        return response()->json($appointments, 200);
    }
}
