<?php

namespace App\Http\Controllers\tutor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Booking;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BookingController extends Controller
{
    // TODO: evtl. unnötig?
    // alle Buchungen
    public function index(): JsonResponse {
        $tutor = auth()->user();

        $bookings = Booking::where('tutor_id', $tutor->id) // eingeloggter user
            ->with(['appointment', 'student'])
            ->get();

        return response()->json($bookings);
    }

    // offene Anfragen
    public function pending(): JsonResponse {
        $tutor = auth()->user();

        // TODO: prüfen
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

        $bookings = Booking::where('tutor_id', $tutor->id)
            ->whereIn('status', ['pending', 'rejected']) // status pending oder rejected
            ->with(['appointment', 'appointment.lesson', 'student'])
            ->get();

        return response()->json($bookings);
    }

    // Buchung annehmen
    public function accept($bookingId): JsonResponse {
        $booking = Booking::where('id', $bookingId)->first();

        if($booking) {
            if(!Gate::allows('own-booking', $booking)) {
                return response()->json("User is not allowed to accept this booking request (no tutor)");
            }

            $booking->status = 'accepted';
            $booking->save();

            $booking->appointment->status = 'booked';
            $booking->appointment->save();

            return response()->json('booking (' .$bookingId .') accepted', 200);

        } else {
            return response()->json('booking (' .$bookingId .') not found', 404);
        }
    }

    // Buchung ablehnen
    public function reject($bookingId): JsonResponse
    {
        $booking = Booking::where('id', $bookingId)->first();

        if($booking) {
            if(!Gate::allows('own-booking', $booking)) {
                return response()->json("User is not allowed to accept this booking request (no tutor)");
            }

            $booking->status = 'rejected';
            $booking->save();

            return response()->json('booking (' .$bookingId .') rejected', 200);

        } else {
            return response()->json('booking (' .$bookingId .') not found', 404);
        }
    }

    // Anstehende gebuchte Termine
    public function upcoming(): JsonResponse {
        $tutor = auth()->user();

        $bookings = Booking::where('tutor_id', $tutor->id)
            ->where('status', 'accepted')
            ->with(['appointment', 'student'])
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
        $upcomingBookings = Booking::where('tutor_id', $tutor->id)
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
            ->with(['appointment', 'student'])
            ->get();

        return response()->json($upcomingBookings);
    }

    // Vergangene Termine
    public function finished(): JsonResponse
    {
        $tutor = auth()->user();

        $bookings = Booking::where('tutor_id', $tutor->id)
            ->where('status', 'finished') // status wird automatisch bei aufrufen der pending appoint. geprüft und gesetzt
            ->with(['appointment', 'appointment.lesson', 'student'])
            ->get();

        return response()->json($bookings);
    }
}
