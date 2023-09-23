<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MyCustomMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // dd(Auth::user('api'));
        if (Auth::guard('api')->check()) {
            if (Auth('api')->user()->status != 'active' || Auth('api')->user()->deleted_at != null) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            return $next($request);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
