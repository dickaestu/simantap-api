<?php

namespace App\Http\Controllers;

use App\Models\StatusSuratKeluar;
use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

use Tymon\JWTAuth\Facades\JWTAuth;

class SuratKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = JWTAuth::user();
        $seq = $user->bagian->seq;

        if ($seq == 5 || $seq == 4) {
            if ($request->keyword && $request->start_date == "" && $request->end_date == "") {
                $data = SuratKeluar::with(['created_by', 'updated_by', 'status_surat', 'bagian'])
                    ->orWhere(
                        function ($query) use ($request) {
                            $query->where(
                                'pengolah',
                                'like',
                                '%' . $request->keyword . '%'
                            )->orWhere(
                                'tujuan_surat',
                                'like',
                                '%' . $request->keyword . '%'
                            )->orWhere(
                                'perihal',
                                'like',
                                '%' . $request->keyword . '%'
                            )->orWhere(
                                'no_surat',
                                'like',
                                '%' . $request->keyword . '%'
                            );
                        }
                    )->where('status', '<', 3)->where('bagian_id', $user->bagian->bagian_id)->orderBy('created_at', 'desc')->get();
            } else if ($request->start_date && $request->end_date) {
                $data = SuratKeluar::with(['created_by', 'updated_by', 'status_surat', 'bagian'])
                    ->whereBetween('tanggal_surat', [$request->start_date, $request->end_date])
                    ->where('status', '<', 3)
                    ->where('bagian_id', $user->bagian->bagian_id)
                    ->orderBy('created_at', 'desc')->get();
            } else if ($request->keyword && $request->start_date && $request->end_date) {
                $data = SuratKeluar::with(['created_by', 'updated_by', 'status_surat', 'bagian'])
                    ->orWhere(
                        function ($query) use ($request) {
                            $query->where(
                                'pengolah',
                                'like',
                                '%' . $request->keyword . '%'
                            )->orWhere(
                                'tujuan_surat',
                                'like',
                                '%' . $request->keyword . '%'
                            )->orWhere(
                                'perihal',
                                'like',
                                '%' . $request->keyword . '%'
                            )->orWhere(
                                'no_surat',
                                'like',
                                '%' . $request->keyword . '%'
                            );
                        }
                    )
                    ->whereBetween('tanggal_surat', [$request->start_date, $request->end_date])
                    ->where('status', '<', 3)
                    ->where('bagian_id', $user->bagian->bagian_id)
                    ->orderBy('created_at', 'desc')->get();
            } else {
                $data = SuratKeluar::with(['created_by', 'updated_by', 'status_surat', 'bagian'])
                    ->where('status', '<', 3)
                    ->where('bagian_id', $user->bagian->bagian_id)
                    ->orderBy('created_at', 'desc')->get();
            }
        } else if ($seq == 3) {
            if ($request->keyword && $request->start_date == "" && $request->end_date == "") {
                $data = SuratKeluar::with(['created_by', 'updated_by', 'status_surat', 'bagian'])
                    ->orWhere(
                        function ($query) use ($request) {
                            $query->where(
                                'pengolah',
                                'like',
                                '%' . $request->keyword . '%'
                            )->orWhere(
                                'tujuan_surat',
                                'like',
                                '%' . $request->keyword . '%'
                            )->orWhere(
                                'perihal',
                                'like',
                                '%' . $request->keyword . '%'
                            )->orWhere(
                                'no_surat',
                                'like',
                                '%' . $request->keyword . '%'
                            );
                        }
                    )->where('status', 2)->where('bagian_id', $user->bagian->bagian_id)->orderBy('created_at', 'desc')->get();
            } else if ($request->start_date && $request->end_date) {
                $data = SuratKeluar::with(['created_by', 'updated_by', 'status_surat', 'bagian'])
                    ->whereBetween('tanggal_surat', [$request->start_date, $request->end_date])
                    ->where('status', 2)
                    ->where('bagian_id', $user->bagian->bagian_id)
                    ->orderBy('created_at', 'desc')->get();
            } else if ($request->keyword && $request->start_date && $request->end_date) {
                $data = SuratKeluar::with(['created_by', 'updated_by', 'status_surat', 'bagian'])
                    ->orWhere(
                        function ($query) use ($request) {
                            $query->where(
                                'pengolah',
                                'like',
                                '%' . $request->keyword . '%'
                            )->orWhere(
                                'tujuan_surat',
                                'like',
                                '%' . $request->keyword . '%'
                            )->orWhere(
                                'perihal',
                                'like',
                                '%' . $request->keyword . '%'
                            )->orWhere(
                                'no_surat',
                                'like',
                                '%' . $request->keyword . '%'
                            );
                        }
                    )
                    ->whereBetween('tanggal_surat', [$request->start_date, $request->end_date])
                    ->where('status', 2)
                    ->where('bagian_id', $user->bagian->bagian_id)
                    ->orderBy('created_at', 'desc')->get();
            } else {
                $data = SuratKeluar::with(['created_by', 'updated_by', 'status_surat', 'bagian'])
                    ->where('status', 2)
                    ->where('bagian_id', $user->bagian->bagian_id)
                    ->orderBy('created_at', 'desc')->get();
            }
        }



        $mappingSuratKeluar = $data->map(function ($item) {
            $item->file_path =
                'https://api.simantap.ngampooz.com/files/surat_keluar/' . $item->file;
            return $item;
        });
        return response()->json([
            'status' => 'Success',
            'data' => $data
        ], 200);
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

            $fileName = now()->toDateString() . '_' . $file->getClientOriginalName();
            $file->move('files/surat_keluar/', $fileName);
            $surat_keluar = SuratKeluar::create([
                'no_surat' => $request->no_surat,
                'tanggal_surat' => $request->tanggal_surat,
                'pengolah' => $request->pengolah,
                'tujuan_surat' => $request->tujuan_surat,
                'perihal' => $request->perihal,
                'keterangan' => $request->keterangan,
                'file' => $fileName,
                'created_by' => $user->id,
                'bagian_id' => $user->bagian->bagian_id
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
        $user = JWTAuth::user();
        $validator = Validator::make($request->all(), [
            'no_surat' => 'required|string|max:50|unique:surat_keluar,no_surat,' . $item->id,
            'tanggal_surat' => 'required|date',
            'pengolah' => 'required|string|max:255',
            'tujuan_surat' => 'required|string|max:255',
            'perihal' => 'required|string|max:255',
            'file' => 'file|mimes:csv,xlsx,xls,pdf,doc,docx|max:5000',
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
            if ($request->file) {
                $file = $request->file('file');

                $fileName = now()->toDateString() . '_' . $file->getClientOriginalName();
                File::delete('files/surat_keluar/' . $item->file);
                $file->move('files/surat_keluar/', $fileName);
            }
            $surat_keluar = $item->update([
                'no_surat' => $request->no_surat,
                'tanggal_surat' => $request->tanggal_surat,
                'pengolah' => $request->pengolah,
                'tujuan_surat' => $request->tujuan_surat,
                'perihal' => $request->perihal,
                'keterangan' => $request->keterangan,
                'file' => $fileName ?? $item->file,
                'updated_by' => $user->id
            ]);
            if ($surat_keluar) {
                $status = "success";
                $message = "Data berhasil diupdate";
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = SuratKeluar::findOrFail($id);
        $item->delete();
        File::delete('files/surat_keluar/' . $item->file);
        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus',
        ], 200);
    }

    public function confirm($id)
    {
        $user = JWTAuth::user();
        $seq = $user->bagian->seq;
        if ($seq == 4) {
            $surat_keluar = SuratKeluar::findOrFail($id);
            $surat_keluar->update([
                'status' => 2
            ]);
        } else if ($seq == 3) {
            $surat_keluar = SuratKeluar::findOrFail($id);
            $surat_keluar->update([
                'status' => 3
            ]);
        } else if ($seq == 2) {
            $surat_keluar = SuratKeluar::findOrFail($id);
            $surat_keluar->update([
                'status' => 4
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Konfirmasi Berhasil',
        ], 200);
    }

    public function history($id)
    {

        $surat_keluar = SuratKeluar::findOrfail($id);

        $data = StatusSuratKeluar::where('id', '<=', $surat_keluar->status)->get();

        return response()->json([
            'status' => 'Success',
            'data' => $data
        ], 200);
    }
}
