<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;

class LessonController extends Controller {
    // TODO: status auf pending setzen
    public function availableByID(string $categoryId): JsonResponse {
        $studentId = auth()->id();

        // alte app. löschen
        Appointment::where('status', 'available')
            ->where(function ($query) {
                $query->where('date', '<', now()->toDateString())
                    ->orWhere(function ($q) {
                        $q->where('date', now()->toDateString())
                            ->where('start', '<=', now()->toTimeString());
                    });
            })
            ->doesntHave('booking')
            ->delete();

        $lessons = Lesson::with('tutor')
            ->with(['appointments' => function ($query) use ($studentId) { // alle Termine
                $query->where('status', 'available') // nur available
                    ->whereDoesntHave('booking', function ($subQuery) use ($studentId) {
                        $subQuery->where('student_id', $studentId); // keine app. die der student schon gebucht hat
                    });
            }])
            ->where('category_id', $categoryId) // nur lessons der geklickten kategorie
            ->whereHas('appointments', function ($query) use ($studentId) { // mind. ein app.
                $query->where('status', 'available')
                    ->whereDoesntHave('booking', function ($subQuery) use ($studentId) {
                        $subQuery->where('student_id', $studentId); // keine app. die der student schon gebucht hat
                    });
            })
            ->get();

        return response()->json($lessons, 200);
    }
}
