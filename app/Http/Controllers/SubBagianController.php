<?php

namespace App\Http\Controllers;

use App\Models\SubBagian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubBagianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = SubBagian::all();

        return response()->json([
            'status' => 'Success',
            'data' => $data
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
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'bagian_id' => 'required',
            'status_bagian' => 'required|in:karo,kabag,kasubag',
            'atasan' => 'nullable'
        ]);

        $status = "error";
        $message = "";
        $data = null;
        $code = 400;

        if ($validator->fails()) {
            $errors = $validator->errors();
            $message = $errors;
        } else {
            if ($request->status_bagian == 'karo') {
                $seq = 1;
            } else if ($request->status_bagian == 'kabag') {
                $seq = 2;
            } else if ($request->status_bagian == 'kasubag') {
                $seq = 3;
            }

            if ($request->status_bagian == 'kasubag') {
                $subbagian = SubBagian::create([
                    'nama' => $request->nama,
                    'seq' => $seq,
                    'bagian_id' => $request->bagian_id,
                    'status_bagian' => $request->status_bagian,
                    'atasan' => $request->atasan ?? null
                ]);

                $paur = SubBagian::create([
                    'nama' => $subbagian->nama . ' paur',
                    'seq' => 4,
                    'bagian_id' => $request->bagian_id,
                    'status_bagian' => 'paur',
                    'atasan' => $subbagian->id
                ]);

                SubBagian::create([
                    'nama' => $paur->nama . ' staffmin',
                    'seq' => 5,
                    'bagian_id' => $request->bagian_id,
                    'status_bagian' => 'bamin',
                    'atasan' => $paur->id
                ]);
            } else {
                $subbagian = SubBagian::create([
                    'nama' => $request->nama,
                    'seq' => $seq,
                    'bagian_id' => $request->bagian_id,
                    'status_bagian' => $request->status_bagian,
                    'atasan' => $request->atasan ?? null
                ]);
            }

            if ($subbagian) {
                $status = "success";
                $message = "Data berhasil dibuat";
                $data = $subbagian;
                $code = 200;
            } else {
                $message = 'Failed';
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

        $item = SubBagian::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
        ]);

        $status = "error";
        $message = "";
        $data = null;
        $code = 400;

        if ($validator->fails()) {
            $errors = $validator->errors();
            $message = $errors;
        } else {


            $subbagian = $item->update([
                'nama' => $request->nama,
            ]);

            if ($subbagian) {
                $status = "success";
                $message = "Data berhasil diupdate";
                $data = $subbagian;
                $code = 200;
            } else {
                $message = 'Failed';
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
