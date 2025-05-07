<?php

namespace App\Http\Controllers\tutor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AppointmentController extends Controller
{
    // TODO: store (neuer Termin), update (bearbeiten), destroy (löschen)

    public function availableByLesson($lessonId): JsonResponse {
        $tutor = auth()->user(); // ruft authentifizierten Benutzer ab
        // nur appointments von aktuellem Tutor über lesson_id
        $appointments = Appointment::where('status', 'available')
            ->where('lesson_id', $lessonId) // Termine zu bestimmter lesson
            ->whereHas('lesson', function ($query) use ($tutor) {
                $query->where('tutor_id', $tutor->id);
            })
            ->orderBy('date')
            ->orderBy('start')
            ->get();
        return response()->json($appointments, 200);
    }

    public function delete(string $appointmentId):JsonResponse {
        $appointment = Lesson::where('id', $appointmentId)->first();
        if($appointment) { // wenn es dieses appoint. gibt
            $appointment->delete();
            return response()->json('appointment (' .$appointmentId .') deleted', 200);
        } else {
            return response()->json('appointment (' .$appointmentId .') not found', 404);
        }
    }
}
