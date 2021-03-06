<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Login and get jwt.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::with(['role', 'bagian.jenis_bagian'])->where('email', $request->username)->orWhere('username', $request->username)->first();
        if (!$user) {
            $response = [
                'message' => 'Email or username not found.'
            ];
            $status   = 404;
        } else {
            if ($user->is_active == 0) {
                $response = [
                    'message' => 'User is not active. Please contact administrator.'
                ];
                $status = 401;
            } else {
                if ($request->device_token) {
                    $user->update([
                        'device_token' => $request->device_token
                    ]);
                }
                if (Hash::check($request->password, $user->password)) {
                    $token = Auth::login($user);

                    $response = $this->respondWithToken($token, $user);
                    $status = 200;
                } else {
                    $response = [
                        'message' => 'password wrong.'
                    ];
                    $status = 401;
                }
            }
        }

        return response()->json($response, $status);
    }

    public function logout(Request $request)
    {

        //Blacklist token from User login
        JWTAuth::invalidate(JWTAuth::parseToken());
        return response()->json([
            'message'   => 'Logout Successfully'
        ], 200);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $user)
    {
        return response()->json([
            'user_data' => $user,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
