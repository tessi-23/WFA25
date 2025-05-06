<?php

namespace App\Http\Controllers\tutor;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
}
