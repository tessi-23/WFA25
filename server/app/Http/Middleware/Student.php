<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Student
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        // prüfen, ob Rolle erlaubt ist
        if ($user == null || $user->role != 'student') {
            return response()->json(['User unauthorized (no student)'], 401);
        }

        return $next($request);
    }
}
