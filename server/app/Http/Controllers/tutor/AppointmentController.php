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
        $request = $this->parseRequest($request);

        DB::beginTransaction(); // alle transactions in eine Warteschlange setzen


        try {
            $appointment = Appointment::create([
                'title' => $request->title,
                'date' => $request->date,
                'start' => $request->start,
                'end' => $request->end,
                'status' => 'available', // TODO: richtig so manuell zu setzen?
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

    private function parseRequest(Request $request): Request {
        $date = new \DateTime($request->date); // date Property in richtiges Format umwandeln
        $request['date'] = $date->format('Y-m-d');

        $start = new \DateTime($request->start);
        $request['start'] = $start->format('H:i:s');

        $end = new \DateTime($request->end);
        $request['start'] = $end->format('H:i:s');
        return $request;
    }
}
