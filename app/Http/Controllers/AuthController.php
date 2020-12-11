<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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

        $user = User::where('email', $request->username)->orWhere('username', $request->username)->first();
        if (!$user) {
            $response = [
                'message' => 'Email or username not found.'
            ];
            $status   = 404;
        } else {
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

        return response()->json($response, $status);
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
            'role'  => $user->role()->select('id','role_name')->first(),
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
