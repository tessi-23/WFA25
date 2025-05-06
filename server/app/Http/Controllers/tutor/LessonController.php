<?php

namespace App\Http\Controllers\tutor;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function index(): JsonResponse {
        $user = auth()->user(); // ruft authentifizierten Benutzer ab
        $lessons = Lesson::where('tutor_id', $user->id) // eingeloggter tutor
            ->whereHas('appointments', function ($query) { // lesson hat mind. ein verfügbares appoint.
                $query->where('status', 'available');
            })
            ->with('appointments')
            ->get();

        return response()->json($lessons, 200);
    }



}
