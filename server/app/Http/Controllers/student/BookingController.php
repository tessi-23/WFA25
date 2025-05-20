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

        $expiredAppointments = Appointment::where('status', 'available')
            ->where(function ($query) {
                $query->where('date', '<', now()->toDateString())
                    ->orWhere(function ($query) {
                        $query->where('date', '=', now()->toDateString())
                            ->where('start', '<=', now()->toTimeString());
                    });
            })
            ->doesntHave('booking') // app. hat keine zugehörigen bookings
            ->get();

        foreach ($expiredAppointments as $appointment) {
            $appointment->delete();
        }

        $bookings = Booking::where('student_id', $student->id)
            ->whereIn('status', ['pending', 'rejected']) // status pending oder rejected
            ->with(['appointment', 'appointment.lesson', 'tutor'])
            ->get();

        return response()->json($bookings);
    }

    // Anstehende gebuchte Termine
    public function upcoming(): JsonResponse {
        $student = auth()->user();

        $bookings = Booking::where('student_id', $student->id)
            ->where('status', 'accepted')
            ->with(['appointment', 'tutor'])
            ->get();


        foreach ($bookings as $booking) {
            $appointment = $booking->appointment; // entsprechender Termin pro Buchung

            // TODO: prüfen, funktioniert evtl. noch nicht?
            // wenn Termin abgelaufen status auf finished setzen
            if ($appointment->date < now()->toDateString() ||
                $appointment->date == now()->toDateString() && $appointment->start < now()->toTimeString()) {
                $booking->status = 'finished';
                $booking->save();
            }
        }

        // jetzt alle zukünftigen accepted Buchungen heraussuchen
        $upcomingBookings = Booking::where('student_id', $student->id)
            ->where('status', 'accepted')
            ->whereHas('appointment', function ($query1) { // in appointments zeit prüfen
                $query1->where(function ($query2) {
                    $query2->where('date', '>', now()->toDateString()) // datum liegt in Zukunft
                    ->orWhere(function ($query3) {
                        $query3->where('date', now()->toDateString()) // Das Ende liegt in Zukunft
                        ->where('end', '>', now()->toTimeString());
                    });
                });
            })
            ->with(['appointment', 'tutor'])
            ->get();

        return response()->json($upcomingBookings);
    }

    // Vergangene Termine
    public function finished(): JsonResponse
    {
        $student = auth()->user();

        $bookings = Booking::where('student_id', $student->id)
            ->where('status', 'finished') // status wird automatisch bei aufrufen der pending appoint. geprüft und gesetzt
            ->with(['appointment', 'appointment.lesson', 'tutor'])
            ->get();

        return response()->json($bookings);
    }
}
