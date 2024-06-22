<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if ($user instanceof \App\Models\User) {
            // Hinting here for $user will be specific to the User object
            if (Auth::check()) {
                if ($user->tokenCan('server:admin')) {
                    return $next($request);
                } else {
                    return response()->json([
                        'status' => 403,
                        'message' => 'Access denied!'
                    ], 403);
                }
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Please login first!'
                ]);
            }
        } else {
            // Handle Error. Not logged in or guard did not return a User object.
        }
    }
}
