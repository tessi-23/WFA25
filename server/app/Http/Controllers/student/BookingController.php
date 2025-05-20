<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Booking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingController extends Controller {
    // requests
    public function pending(): JsonResponse {
        $student = auth()->user();

        // TODO: prüfen
        $expiredAppointments = Appointment::where('date', '<', now()->toDateString())
            ->orWhere(function ($query) {
                $query->where('date', '=', now()->toDateString())
                    ->where('start', '<=', now()->toTimeString());
            })->get();

        foreach ($expiredAppointments as $appointment) {
            // Wenn gebucht, vorher die Bookings löschen
            if ($appointment->status === 'booked') {
                Booking::where('appointment_id', $appointment->id)->delete();
            }
            // Danach das Appointment selbst löschen
            $appointment->delete();
        }

        $bookings = Booking::where('student_id', $student->id)
            ->where('status', 'pending')
            ->with(['appointment', 'appointment.lesson', 'tutor'])
            ->get();

        return response()->json($bookings);
    }
}
