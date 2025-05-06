<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    function index(): JsonResponse {
        $lessons = Lesson::with('tutor')->get();
        return response()->json($lessons, 200);
    }
    public function findByID(string $id): JsonResponse {
        $lesson = Lesson::where('id', $id)->first();
        return $lesson != null ? response()->
        json($lesson, 200) : response()->
        json(null, 200);
    }
}
