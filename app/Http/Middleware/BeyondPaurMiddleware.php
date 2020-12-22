<?php

namespace App\Http\Middleware;

use Closure;

use Tymon\JWTAuth\Facades\JWTAuth;

class BeyondPaurMiddleware
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

        if($user->bagian->bagian_id == 2){
            if($user->bagian->seq > 3){
                return response()->json(['forbidden access. Diatas kaur yang bisa akses.'], 403);
            } else {
                return $next($request);
            }
        } else {
            if($user->bagian->seq > 4){
                return response()->json(['forbidden access. Diatas kaur yang bisa akses.'], 403);
            } else {
                return $next($request);
            }
        }
        
    }
}
