<?php

namespace App\Http\Controllers\tutor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class AppointmentController extends Controller
{
    // TODO: prüfen
    public function availableByLesson($lessonId): JsonResponse {

        $expiredAppointments = Appointment::where('date', '<', now()->toDateString())
            ->orWhere(function ($query) {
                $query->where('date', '=', now()->toDateString())
                    ->where('start', '<=', now()->toTimeString());
            })->get();
        foreach ($expiredAppointments as $appointment) {
            $appointment->delete(); // Termin direkt löschen
        }

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
        $appointment = Appointment::where('id', $appointmentId)->first();
        if($appointment) { // wenn es dieses appoint. gibt
            if(!Gate::allows('own-appointment', $appointment)) { // nur eigene appoint. löschbar
                return response()->json("User is not allowed to delete this appointment (no tutor)");
            }
            $appointment->delete();
            return response()->json('appointment (' .$appointmentId .') deleted', 200);
        } else {
            return response()->json('appointment (' .$appointmentId .') not found', 404);
        }
    }

    public function store(Request $request): JsonResponse {
        // Uhrzeit und Datum prüfen
        if (!$this->isFutureDateTime($request->date, $request->start)) {
            return response()->json(["error" => "Appointment date and time must be in the future."], 400);
        }
        $request = $this->parseRequest($request);

        DB::beginTransaction(); // alle transactions in eine Warteschlange setzen


        try {
            $appointment = Appointment::create([
                'title' => $request->title,
                'date' => $request->date,
                'start' => $request->start,
                'end' => $request->end,
                'status' => 'available',
                'price' => $request->price,
                'lesson_id' => $request->lesson_id,
            ]);

            if (!$appointment) {
                throw new \Exception("appointment could not be created");
            }

            DB::commit();
            return response()->json($appointment, 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "saving appointment failed:  " . $e->getMessage()], 500);
        }
    }

    function update(Request $request, string $appointmentId): JsonResponse {
        DB::beginTransaction();
        try {
            $appointment = Appointment::where('id', $appointmentId)->first();
            if($appointment) {
                if(!Gate::allows('own-appointment', $appointment)) {
                    return response()->json("User is not allowed to update this appointment (no tutor)", 403);
                }
                // Uhrzeit und Datum prüfen
                if (!$this->isFutureDateTime($request->date, $request->start)) {
                    return response()->json(["error" => "Appointment date and time must be in the future."], 400);
                }
                $request = $this->parseRequest($request);

                $appointment->update($request->all());
                DB::commit();
                return response()->json($appointment, 200);
            } else {
                return response()->json('appointment (' .$appointmentId .') not found', 404);
            }

        } catch (\Exception $e) {
            DB::rollBack(); // nichts wird gespeichert
            return response()->json(["updating appointment failed: ". $e->getMessage()], 500);
        }
    }

    private function parseRequest(Request $request): Request {
        $date = new \DateTime($request->date); // date Property in richtiges Format umwandeln
        $request['date'] = $date->format('Y-m-d');

        $start = new \DateTime($request->start);
        $request['start'] = $start->format('H:i:s');

        $end = new \DateTime($request->end);
        $request['end'] = $end->format('H:i:s');
        return $request;
    }

    // TODO: prüfen, funktioniert noch nicht
    private function isFutureDateTime(string $date, string $time): bool {
        try {
            // Kombiniere Datum und Zeit in ein DateTime-Objekt
            $inputDateTime = new \DateTime("$date $time");
            $now = new \DateTime();

            // Nutze den Timestamp-Vergleich
            return $inputDateTime->getTimestamp() > $now->getTimestamp();
        } catch (\Exception $e) {
            throw new \Exception("Invalid date or time format: " . $e->getMessage());
        }
    }

}
