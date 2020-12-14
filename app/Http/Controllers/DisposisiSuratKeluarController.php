<?php

namespace App\Http\Controllers;

use App\Models\Disposition;
use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DisposisiSuratKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dispositions = Disposition::where('disposable_type', 'App\Models\SuratKeluar')->get();

        $mappingDispositions = $dispositions->map(function ($item) {
            $item->tembusan = $item->sections()->get();

            return $item;
        });

        return response()->json([
            'message' => 'Success',
            'data' => $mappingDispositions
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
    public function store(Request $request, $suratId)
    {
        $validator = Validator::make($request->all(), [
            'kepada'  => 'required|numeric',
            'catatan' => 'required',
        ]);

        $status = "error";
        $message = "";
        $code = 400;

        if ($validator->fails()) {
            $errors = $validator->errors();
            $message = $errors;
        } else {
            $outcomingMessage = SuratKeluar::FindOrFail($suratId);
            $disposition = $outcomingMessage->dispositions()->create([
                'kepada'  => $request->kepada,
                'catatan' => $request->catatan
            ]);

            if ($tembusan = $request->tembusan) {
                $disposition->sections()->sync($tembusan);
            }
            if ($disposition) {
                $status = "success";
                $message = "Data berhasil dibuat";
                $code = 200;
            } else {
                $message = 'Failed';
            }
        }
        return response()->json([
            'status' => $status,
            'message' => $message,
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
        $validator = Validator::make($request->all(), [
            'kepada'  => 'required|numeric',
            'catatan' => 'required',
        ]);

        $status = "error";
        $message = "";
        $code = 400;

        if ($validator->fails()) {
            $errors = $validator->errors();
            $message = $errors;
        } else {
            $disposition = Disposition::FindOrFail($id);
            $disposition->update([
                'kepada'  => $request->kepada,
                'catatan' => $request->catatan
            ]);

            if ($tembusan = $request->tembusan) {
                $disposition->sections()->sync($tembusan);
            }
            if ($disposition) {
                $status = "success";
                $message = "Data berhasil diupdate";
                $code = 200;
            } else {
                $message = 'Failed';
            }
        }
        return response()->json([
            'status' => $status,
            'message' => $message,
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
        $disposition = Disposition::findOrFail($id);
        $disposition->sections()->sync([]);
        $disposition->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus',
        ], 200);
    }
}
