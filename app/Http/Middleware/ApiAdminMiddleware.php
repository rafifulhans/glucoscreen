<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // For API, check if user is authenticated via sanctum and has admin role
        $user = auth()->guard('sanctum')->user();
        
        // Kader authenticates via Kader model, which has a relationship to User model
        // Check if the related User has admin role
        if (!$user) {
            abort(403, 'Unauthorized access. Only admin can access this resource.');
        }

        // If user is already a User model (direct admin login), check role directly
        if ($user instanceof \App\Models\User) {
            if ($user->role === 'admin') {
                return $next($request);
            }
            abort(403, 'Unauthorized access. Only admin can access this resource.');
        }

        // If user is Kader model, check the related User's role
        if ($user instanceof \App\Models\Kader && method_exists($user, 'user')) {
            try {
                $relatedUser = $user->user;
                if ($relatedUser && $relatedUser->role === 'admin') {
                    return $next($request);
                }
            } catch (\Throwable $e) {
                // If relationship fails, deny access
            }
        }

        abort(403, 'Unauthorized access. Only admin can access this resource.');
    }
}
