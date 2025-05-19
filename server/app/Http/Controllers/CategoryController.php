<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    function index(): JsonResponse {
        $categories = Category::with('lessons')->whereHas('lessons')->get();
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

    // nur admin
    public function store(Request $request): JsonResponse {
        DB::beginTransaction(); // alle transactions in eine Warteschlange setzen

        try {
            $category = Category::create([
                'title' => $request->title,
                'description' => $request->description
            ]);

            if (!$category) {
                throw new \Exception("category could not be created");
            }

            DB::commit();
            return response()->json($category, 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => "saving category failed:  " . $e->getMessage()], 500);
        }
    }

    // nur admin
    function update(Request $request, string $categoryId): JsonResponse {
        DB::beginTransaction();
        try {
            $category = Category::where('id', $categoryId)->first(); // aktuelle category
            if($category) {
                $category->update($request->all());
                DB::commit();
                return response()->json($category, 200);
            } else {
                return response()->json('category (' .$categoryId .') not found', 404);
            }

        } catch (\Exception $e) {
            DB::rollBack(); // nichts wird gespeichert
            return response()->json(["updating category failed: ". $e->getMessage()], 500);
        }
    }
}
