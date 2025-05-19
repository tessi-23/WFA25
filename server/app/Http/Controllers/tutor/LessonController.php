<?php

namespace App\Http\Controllers\tutor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class LessonController extends Controller
{
    public function availableByID(string $categoryId): JsonResponse {
        $tutor = auth()->user(); // ruft authentifizierten Benutzer ab
        $lessons = Lesson::where('tutor_id', $tutor->id) // eingeloggter tutor
            ->where('category_id', $categoryId)
            ->whereHas('appointments', function ($query) { // lesson hat mind. ein verfügbares appoint.
                $query->where('status', 'available');
            })
            ->with(['appointments' => function ($query) { // Verfügbare Appointments laden
                $query->where('status', 'available');
            }])
            ->withCount(['appointments' => function ($query) use ($tutor) {
                $query->where('tutor_id', $tutor->id); // Anzahl appointments
            }])
            ->get();

        return response()->json($lessons, 200);
    }

    public function delete(string $lessonId):JsonResponse {
        $lesson = Lesson::where('id', $lessonId)->first();
        if($lesson) { // wenn es diese lesson gibt
            if(!Gate::allows('own-lesson', $lesson)) { // die User Instanz ist autom. drin
                return response()->json("User is not allowed to delete this lesson (no tutor)");
            }
            $lesson->delete();
            return response()->json('lesson (' .$lessonId .') deleted', 200);
        } else {
            return response()->json('lesson (' .$lessonId .') not found', 404);
        }
    }

    public function store(Request $request): JsonResponse {
        $tutor = auth()->user(); // ruft authentifizierten Benutzer ab
        DB::beginTransaction(); // alle transactions in eine Warteschlange setzen

        try {
            $lesson = Lesson::create([
                'title' => $request->title,
                'description' => $request->description,
                'tutor_id' => $tutor->id, // aktuelle id vom tutor speichern
                'category_id' => $request->category_id,
            ]);

            if (!$lesson) {
                throw new \Exception("lesson could not be created");
            }

            if (isset($request['appointments']) && is_array($request['appointments'])) {
                foreach ($request['appointments'] as $appData) {
                    $appointment = new Appointment();
                    $appointment->title = $appData['title'];
                    $appointment->date = $appData['date'];
                    $appointment->start = $appData['start'];
                    $appointment->end = $appData['end'];
                    $appointment->status = 'available';
                    $appointment->price = $appData['price'];
                    $appointment->lesson_id = $lesson->id;
                    $lesson->appointments()->save($appointment);
                }
            }

            DB::commit();
            return response()->json($lesson, 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "saving lesson failed:  " . $e->getMessage()], 500);
        }
    }

    function update(Request $request, string $lessonId): JsonResponse {
        DB::beginTransaction();
        try {
            $lesson = Lesson::where('id', $lessonId)->first();
            if($lesson) {
                if(!Gate::allows('own-lesson', $lesson)) {
                    return response()->json("User is not allowed to update this lesson (no tutor)", 403);
                }

                $lesson->update($request->all());

                // zugehörige appointments löschen und neu speichern
                $lesson->appointments()->delete();

                if (isset($request['appointments']) && is_array($request['appointments'])) {
                    foreach ($request['appointments'] as $appData) {
                        $appointment = new Appointment();
                        $appointment->title = $appData['title'];
                        $appointment->date = $appData['date'];
                        $appointment->start = $appData['start'];
                        $appointment->end = $appData['end'];
                        $appointment->status = 'available';
                        $appointment->price = $appData['price'];
                        $appointment->lesson_id = $lesson->id;
                        $lesson->appointments()->save($appointment);
                    }
                }

                DB::commit();
                return response()->json($lesson, 200);
            } else {
                return response()->json('lesson (' .$lessonId .') not found', 404);
            }

        } catch (\Exception $e) {
            DB::rollBack(); // nichts wird gespeichert
            return response()->json(["updating lesson failed: ". $e->getMessage()], 500);
        }
    }
}
