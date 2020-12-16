<?php

namespace App\Http\Controllers;

use App\Models\Bagian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Tymon\JWTAuth\Facades\JWTAuth;

use PDF;

use App\Models\SuratMasuk;

class SuratMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = JWTAuth::user();
        $incomingMessages = SuratMasuk::with(['created_by', 'updated_by'])->whereHas('created_by', function ($item) use ($user) {
            return $item->where('bagian_id', $user->bagian_id);
        })->orderBy('created_at', 'desc')->get();

        return response()->json([
            'message' => 'fetched successfully',
            'data' => $incomingMessages
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
            'no_agenda' => 'required|string|max:50',
            'no_surat' => 'required|string|max:50|unique:surat_masuk',
            'tanggal_surat' => 'required|date',
            'tanggal_terima' => 'required|date',
            'sumber_surat' => 'required|string|max:255',
            'tujuan_surat' => 'required|string|max:255',
            'perihal' => 'required|string|max:255',
            'file.*' => 'required|file|mimes:csv,xlsx,xls,pdf,doc,docx|max:5000',
            'keterangan' => 'nullable',
        ]);

        if ($validator->fails()) {
            $response = [
                'message' => 'Error Validation',
                'errors'  => $validator->messages()
            ];
            $status = 422;
        } else {
            $file = $request->file('file');

            $fileName = time() . '_' . $file->getClientOriginalName();

            $file->move('files/surat_masuk', $fileName);

            SuratMasuk::create([
                'no_agenda' => $request->no_agenda,
                'no_surat' => $request->no_surat,
                'tanggal_surat' => $request->tanggal_surat,
                'tanggal_terima' => $request->tanggal_terima,
                'sumber_surat' => $request->sumber_surat,
                'tujuan_surat' => $request->tujuan_surat,
                'perihal' => $request->perihal,
                'file' => $fileName,
                'keterangan' => $request->keterangan,
                'created_by' => $user->id
            ]);

            $response = [
                'message' => 'stored successfully'
            ];
            $status = 201;
        }

        return response()->json($response, $status);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $message = SuratMasuk::FindOrFail($id);

        return response()->json([
            'message' => 'fetched successfully',
            'data'    => $message
        ], 200);
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
        $message = SuratMasuk::FindOrFail($id);
        $validator = Validator::make($request->all(), [
            'no_agenda' => 'required|string|max:50',
            'no_surat' => 'required|string|max:50|unique:surat_masuk,no_surat,' . $message->id,
            'tanggal_surat' => 'required|date',
            'tanggal_terima' => 'required|date',
            'sumber_surat' => 'required|string|max:255',
            'tujuan_surat' => 'required|string|max:255',
            'perihal' => 'required|string|max:255',
            'file' => 'file|mimes:csv,xlsx,xls,pdf,doc,docx|max:5000',
            'keterangan' => 'nullable',

        ]);

        if ($validator->fails()) {
            $response = [
                'message' => 'Error Validation',
                'errors'  => $validator->messages()
            ];
            $status = 422;
        } else {
            if ($request->file) {
                $file = $request->file('file');

                $fileName = time() . '_' . $file->getClientOriginalName();
                File::delete('files/surat_masuk/' . $message->file);
                $file->move('files/surat_masuk/', $fileName);
            }

            $message->update([
                'no_agenda' => $request->no_agenda,
                'no_surat' => $request->no_surat,
                'tanggal_surat' => $request->tanggal_surat,
                'tanggal_terima' => $request->tanggal_terima,
                'sumber_surat' => $request->sumber_surat,
                'tujuan_surat' => $request->tujuan_surat,
                'file' => $fileName ?? $message->file,
                'perihal' => $request->perihal,
                'keterangan' => $request->keterangan,
                'updated_by' => $user->id
            ]);

            $response = [
                'message' => 'updated successfully'
            ];
            $status = 200;
        }

        return response()->json($response, $status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $message = SuratMasuk::FindOrFail($id);
        $message->delete();
        File::delete('files/surat_masuk/' . $message->file);

        return response()->json([
            'message' => 'deleted successfully'
        ], 200);
    }

    public function tandaTerima($id, $response)
    {
        $user = JWTAuth::user();
        $message = SuratMasuk::FindOrFail($id);
        $bagian = Bagian::take(4)->get();



        $pdf = PDF::loadView('templates.letter_receipt', [
            'user' => $user,
            'message' => $message,
            'bagian' => $bagian
        ]);

        if ($response == 'view') {
            return $pdf->stream();
        } else {
            return $pdf->download('tanda_terima_surat-' . $message->no_surat . '.pdf');
        }
    }

    public function detailSurat($id, $response)
    {
        $user = JWTAuth::user();
        $message = SuratMasuk::FindOrFail($id);

        $pdf = PDF::loadView('templates.letter_detail', [
            'user' => $user,
            'message' => $message
        ]);

        if ($response == 'view') {
            return $pdf->stream();
        } else {
            return $pdf->download('detail_surat-' . $message->no_surat . '.pdf');
        }
    }
}
