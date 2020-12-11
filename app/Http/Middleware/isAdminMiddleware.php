<?php

namespace App\Http\Middleware;

use Tymon\JWTAuth\Facades\JWTAuth;

use Closure;

class isAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = JWTAuth::user();

        if($user->role->role_name != 'admin'){
            return response()->json([
                'message' => 'Forbidden.'
            ], 403);
        }
        return $next($request);
    }
}
