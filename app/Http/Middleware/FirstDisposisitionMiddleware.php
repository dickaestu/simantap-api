<?php

namespace App\Http\Middleware;

use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Disposition;
use Closure;

class FirstDisposisitionMiddleware
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
        $suratId = $request->surat;
        $countDisposition = Disposition::where('disposable_id', $suratId)->where('disposable_type', 'App\Models\SuratMasuk')->count();

        if($countDisposition == 0 && $user->bagian->seq != 1){
            return response()->json(['message' => 'only karo can disposition first.'], 403);
        }
        return $next($request);
    }
}
