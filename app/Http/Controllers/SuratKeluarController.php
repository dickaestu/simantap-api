<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SuratKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'no_surat' => 'required|string|max:50|unique:surat_keluar',
            'tanggal_surat' => 'required|date',
            'pengolah' => 'required|string|max:255',
            'tujuan_surat' => 'required|string|max:255',
            'perihal' => 'required|string|max:255',
            'file' => 'required|file|mimes:csv,xlsx,xls,pdf,doc,docx|max:5000',
            'keterangan' => 'nullable',
        ]);

        $status = "error";
        $message = "";
        $data = null;
        $code = 400;

        if ($validator->fails()) {
            $errors = $validator->errors();
            $message = $errors;
        } else {
            $file = $request->file('file');

            $fileName = $file->getClientOriginalName();
            $file->move('files\surat keluar', $fileName);
            $surat_keluar = SuratKeluar::create([
                'no_surat' => $request->no_surat,
                'tanggal_surat' => $request->tanggal_surat,
                'pengolah' => $request->pengolah,
                'tujuan_surat' => $request->tujuan_surat,
                'perihal' => $request->perihal,
                'keterangan' => $request->keterangan,
                'file' => $fileName,
                'status' => 'Manager'
            ]);
            if ($surat_keluar) {
                $status = "success";
                $message = "Data berhasil dibuat";
                $data = $surat_keluar;
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $item = SuratKeluar::findOrFail($id);

        return response()->json([
            'message' => 'data berhasil diupdate'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = SuratKeluar::find($id);

        if ($item) {
            $item->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil dihapus',
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan',
            ], 404);
        }
    }
}
