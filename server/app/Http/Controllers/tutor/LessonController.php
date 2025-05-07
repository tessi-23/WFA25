<?php

namespace App\Http\Controllers\tutor;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LessonController extends Controller
{
    public function index(): JsonResponse {
        $tutor = auth()->user(); // ruft authentifizierten Benutzer ab
        $lessons = Lesson::where('tutor_id', $tutor->id) // eingeloggter tutor
            ->whereHas('appointments', function ($query) { // lesson hat mind. ein verfügbares appoint.
                $query->where('status', 'available');
            })
            ->withCount(['appointments' => function ($query) use ($tutor) {
                $query->where('tutor_id', $tutor->id); // Anzahl appointments
            }])
            ->with('appointments')
            ->get();

        return response()->json($lessons, 200);
    }

    public function delete(string $lessonId):JsonResponse {
        $lesson = Lesson::where('id', $lessonId)->first();
        if($lesson) { // wenn es diese lesson gibt
            $lesson->delete();
            return response()->json('lesson (' .$lessonId .') deleted', 200);
        } else {
            return response()->json('lesson (' .$lessonId .') not found', 404);
        }
    }
}
