<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SubBagian;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = JWTAuth::user();
        $seq = $user->bagian->seq;

        if($user->bagian->bagian_id == 2 && $seq == 3 || $seq == 4){
            $bagian = explode(' ', $user->bagian->nama)[1];
            $staff = SubBagian::with('jenis_bagian')->select('id', 'nama', 'seq', 'bagian_id')->where('seq', $seq + 1)->where('bagian_id', $user->bagian->bagian_id)->where('nama', 
            'like', '%'.$bagian)->first();

            $users = User::whereHas('bagian', function($query) use($bagian,$seq){
                $query->where('nama', 'like', '%'. $bagian)->where('seq', $seq+1);
            })->get();
        } else {
            $users = User::All();
        }

        return response()->json([
            'message' => 'fetched all successfully.',
            'data' => $users
        ],200);
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
        if($user->bagian->bagian_id == 2 && $seq == 3 || $seq == 4){
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'username' => 'required|string|max:20|unique:users',
                'password' => 'required|string|min:8',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'username' => 'required|string|max:20|unique:users',
                'password' => 'required|string|min:8',
                'roles_id' => 'required|numeric',
                'bagian_id' => 'required|numeric',
            ]);
        }

        $status = "error";
        $message = "";
        $data = null;
        $code = 400;

        if ($validator->fails()) {
            $errors = $validator->errors();
            $message = $errors;
        } else {
            if($user->bagian->bagian_id == 2 && $seq == 3 || $seq == 4){
                $bagian = explode(' ', $user->bagian->nama)[1];
                $staff = SubBagian::with('jenis_bagian')->select('id', 'nama', 'seq', 'bagian_id')->where('seq', $seq + 1)->where('bagian_id', $user->bagian->bagian_id)->where('nama', 
                'like', '%'.$bagian)->first();
                $request->roles_id = 4;
                $request->bagian_id = $staff->id;
            }
            $userCreated = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'roles_id' => $request->roles_id,
                'sub_bagian_id' => $request->bagian_id,
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
        ],200);
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
        if($user->bagian->bagian_id == 2 && $seq == 3 || $seq == 4){
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $userFind->id,
                'username' => 'required|string|max:20|unique:users,username,' . $userFind->id,
                'password' => 'required|string|min:8',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $userFind->id,
                'username' => 'required|string|max:20|unique:users,username,'. $userFind->id,
                'password' => 'required|string|min:8',
                'roles_id' => 'required|numeric',
                'bagian_id' => 'required|numeric',
            ]);
        }

        $status = "error";
        $message = "";
        $data = null;
        $code = 400;

        if ($validator->fails()) {
            $errors = $validator->errors();
            $message = $errors;
        } else {
            if($user->bagian->bagian_id == 2 && $seq == 3 || $seq == 4){
                $bagian = explode(' ', $user->bagian->nama)[1];
                $staff = SubBagian::with('jenis_bagian')->select('id', 'nama', 'seq', 'bagian_id')->where('seq', $seq + 1)->where('bagian_id', $user->bagian->bagian_id)->where('nama', 
                'like', '%'.$bagian)->first();
                $request->roles_id = 4;
                $request->bagian_id = $staff->id;
            }
            if (!Hash::check($request->password, $user->password)) {
                $request->password = Hash::make($request->password);
            }
            $userFind->update([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => $request->password,
                'roles_id' => $request->roles_id,
                'sub_bagian_id' => $request->bagian_id,
            ]);
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
        $user = User::destroy($id);

        return response()->json([
            'message' => 'deleted successfully.',
        ],200);
    }
}
