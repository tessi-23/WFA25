<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    function index(): JsonResponse {
        $categories = Category::all();
        return response()->json($categories, 200);
    }

    public function findByID(string $id): JsonResponse {
        $category = Category::where('id', $id)->first();
        return $category != null ? response()->
            json($category, 200) : response()->
            json(null, 200);
    }

    // nur admin
    public function delete(string $categoryId):JsonResponse {
        $category = Category::where('id', $categoryId)->first();
        if($category) {
            $category->delete();
            return response()->json('category (' .$categoryId .') deleted', 200);
        } else {
            return response()->json('category (' .$categoryId .') not found', 404);
        }
    }
}
