<?php

namespace App\Http\Controllers\tutor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(): JsonResponse {
        $tutor = auth()->user(); // ruft authentifizierten Benutzer ab
        $categories = Category::whereHas('lessons', function ($query) use ($tutor) {
                $query->where('tutor_id', $tutor->id); // category mit mind. einer verfügbaren lesson
            })
            ->withCount(['lessons' => function ($query) use ($tutor) { // Anzahl der lessons
                $query->where('tutor_id', $tutor->id);
            }])
            ->get();

        return response()->json($categories, 200);
    }
}
