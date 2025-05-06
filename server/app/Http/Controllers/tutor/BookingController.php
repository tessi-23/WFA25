<?php

namespace App\Http\Controllers\tutor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Booking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

        $bookings = Booking::where('tutor_id', $tutor->id)
            ->where('status', 'pending')
            ->with(['appointment', 'appointment.lesson', 'student'])
            ->get();

        return response()->json($bookings);
    }

    // Buchung annehmen
    // TODO: später prüfen/testen
    public function accept($id): JsonResponse {
        $booking = Booking::findOrFail($id);

        //$this->authorize('tutorBooking', $booking); // optional Gate/Policy

        $booking->status = 'accepted';
        $booking->save();

        // Appointment auf 'booked' setzen (optional)
        $booking->appointment->status = 'booked';
        $booking->appointment->save();

        return response()->json(['message' => 'Booking accepted']);
    }

    // Buchung ablehnen
    // TODO: später prüfen/testen
    public function reject($id): JsonResponse
    {
        $booking = Booking::findOrFail($id);

        $this->authorize('tutorBooking', $booking);

        $booking->status = 'rejected';
        $booking->save();

        return response()->json(['message' => 'Booking rejected']);
    }

    // Anstehende gebuchte Termine
    // TODO: später prüfen/testen
    public function upcoming(): JsonResponse {
        $tutor = auth()->user();

        $bookings = Booking::where('tutor_id', $tutor->id)
            ->where('status', 'accepted')
            ->whereHas('appointment', function ($query) {
                $query->where(function ($sub) {
                    $sub->where('date', '>', now()->toDateString())
                        ->orWhere(function ($q) {
                            $q->where('date', now()->toDateString())
                                ->where('end', '>', now()->toTimeString());
                        });
                });
            })
            ->with(['appointment', 'student'])
            ->get();

        return response()->json($bookings);
    }

    // Vergangene Termine
    // TODO: später prüfen/testen
    public function history(): JsonResponse
    {
        $tutor = auth()->user();

        $bookings = Booking::where('tutor_id', $tutor->id)
            ->where('status', 'accepted') // oder auch 'finished' wenn du es irgendwann setzen willst
            ->whereHas('appointment', function ($query) {
                $query->where(function ($q) {
                    $q->where('date', '<', now()->toDateString())
                        ->orWhere(function ($q2) {
                            $q2->where('date', now()->toDateString())
                                ->where('end', '<=', now()->toTimeString());
                        });
                });
            })
            ->with(['appointment', 'student'])
            ->get();

        return response()->json($bookings);
    }






    public function booked(): JsonResponse {
        $user = auth()->user(); // ruft authentifizierten Benutzer ab
        $appointments = Booking::where('status', 'booked')
            ->whereHas('lesson', function ($query) use ($user) {
                $query->where('tutor_id', $user->id);
            })
            ->with(['lesson', 'appointment', 'student'])
            ->get();

        return response()->json($appointments, 200);
    }

    public function done(): JsonResponse {
        $user = auth()->user(); // ruft authentifizierten Benutzer ab
        $appointments = Booking::where('status', 'finished')
            ->where('tutor_id', $user->id)
            ->with(['appointment', 'student'])
            ->get();

        return response()->json($appointments, 200);
    }
}
