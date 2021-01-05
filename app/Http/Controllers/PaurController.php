<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SubBagian;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class PaurController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = JWTAuth::user();
        $sub_bagian_id = $user->bagian->id;

        $sub_bagian = SubBagian::where('atasan', $sub_bagian_id)->select('id')->first();

        if ($request->keyword) {
            $users = User::where('sub_bagian_id', $sub_bagian->id)
                ->where('name', 'like', '%' . $request->keyword . '%')->get();
        } else {
            $users = User::where('sub_bagian_id', $sub_bagian->id)->get();
        }


        return response()->json([
            'message' => 'fetched all successfully.',
            'data' => $users
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = JWTAuth::user();
        $seq = $user->bagian->seq;
        $sub_bagian_id = $user->bagian->id;

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $status = "error";
        $message = "";
        $data = null;
        $code = 400;

        if ($validator->fails()) {
            $errors = $validator->errors();
            $message = $errors;
        } else {
            $atasan = SubBagian::where('atasan', $sub_bagian_id)->select('id')->first();

            $userCreated = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'roles_id' => 4,
                'sub_bagian_id' => $atasan->id,
            ]);
            if ($userCreated) {
                $status = "success";
                $message = "register successfully";
                $data = $userCreated->toArray();
                $code = 201;
            } else {
                $message = 'register failed';
            }
        }
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::FindOrFail($id);

        return response()->json([
            'message' => 'fetched successfully.',
            'data' => $user
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = JWTAuth::user();
        $seq = $user->bagian->seq;
        $userFind = User::FindOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $userFind->id,
            'username' => 'required|string|max:20|unique:users,username,' . $userFind->id,
            'password' => 'string|min:8',
        ]);
        $status = "error";
        $message = "";
        $data = null;
        $code = 400;


        if ($validator->fails()) {
            $errors = $validator->errors();
            $message = $errors;
        } else {
            if ($request->password) {
                $userFind->update([
                    'name' => $request->name,
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'is_active' => $request->is_active ?? $userFind->is_active
                ]);
            } else {
                $userFind->update([
                    'name' => $request->name,
                    'username' => $request->username,
                    'email' => $request->email,
                    'is_active' => $request->is_active ?? $userFind->is_active
                ]);
            }
            if ($user) {
                $status = "success";
                $message = "updated successfully";
                $data = $userFind->toArray();
                $code = 200;
            } else {
                $message = 'updated failed';
            }
        }
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
