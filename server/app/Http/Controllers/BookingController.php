<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Booking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    // für student
    public function store(Request $request): JsonResponse {
        $student = auth()->user(); // ruft authentifizierten Benutzer ab
        DB::beginTransaction(); // alle transactions in eine Warteschlange setzen

        try {
            // $appointment = Appointment::findOrFail('appointment_id'); // id aus frontend in appointment suchen
            // $tutorId = $appointment->lesson->tutor_id; // TutorId aus der zugehörigen Lesson abrufen


            $booking = Booking::create([
                'status' => 'pending',
                'comment' => $request->comment,
                'appointment_id' => $request->appointment_id, // $appointment->id,
                'tutor_id' => $request->tutor_id,
                'student_id' => $student->id,
            ]);

            if (!$booking) {
                throw new \Exception("booking request could not be created");
            }

            DB::commit();
            return response()->json($booking, 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "saving booking request failed:  " . $e->getMessage()], 500);
        }
    }
}
