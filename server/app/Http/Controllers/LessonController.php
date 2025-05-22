<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function availableByID(string $categoryId): JsonResponse {
        $lessons = Lesson::with('tutor')
            ->with(['appointments' => function ($query) {
                $query->where('status', 'available');
            }])
            ->where('category_id', $categoryId)
        ->whereHas('appointments', function ($query) {
                $query->where('status', 'available');
        })
        ->get();
        return response()->json($lessons, 200);
    }

    public function findByID(string $id): JsonResponse {
        $lesson = Lesson::with('appointments')->where('id', $id)->first();
        return $lesson != null ? response()->
        json($lesson, 200) : response()->
        json(null, 200);
    }
}
