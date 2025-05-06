<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Closure;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\JWTAuth;

class Admin
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
        if ($user == null) {
            return response()->json(['User null'], 401);
        } elseif ($user->role !== 'admin') {
            return response()->json(['User unauthorized (no admin)'], 401);
        }

        return $next($request);
    }
}
