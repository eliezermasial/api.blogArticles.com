<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifie si l'utilisateur est authentifié
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // Vérifie si le rôle de l'utilisateur est bien 'admin'
        if (auth()->user()->role->name !== 'admin') {
            return response()->json(['error' => 'Forbidden - Admins only'], 403);
        }
        
        return $next($request);
    }
}
