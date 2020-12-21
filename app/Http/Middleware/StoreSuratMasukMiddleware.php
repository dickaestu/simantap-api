<?php

namespace App\Http\Middleware;

use Tymon\JWTAuth\Facades\JWTAuth;

use Closure;

class StoreSuratMasukMiddleware
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
        $user = JWTAuth::User();
        if($user->bagian->seq != 1){
            return response()->json(['message' => 'Only Karo can create incoming message.'], 403);
        }
        return $next($request);
    }
}
