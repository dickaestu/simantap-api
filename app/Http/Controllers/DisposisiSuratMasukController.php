<?php

namespace App\Http\Controllers;

use App\Models\Disposition;
use App\Models\SuratMasuk;
use Tymon\JWTAuth\Facades\JWTAuth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PDF;

class DisposisiSuratMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = JWTAuth::user();
        $dispositions = Disposition::with(['disposable'])->where('disposable_type', 'App\Models\SuratMasuk')
            ->whereHas('created_by', function ($item) use ($user) {
                return $item->where('bagian_id', $user->bagian_id);
            })->get();

        $mappingDispositions = $dispositions->map(function ($item) {
            $item->tembusan = $item->sections()->get();

            return $item;
        });

        return response()->json([
            'message' => 'fetched successfully',
            'data' => $mappingDispositions
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $suratId)
    {
        $user = JWTAuth::user();
        $validator = Validator::make($request->all(), [
            'kepada'  => 'required|numeric',
            'catatan' => 'required',
        ]);

        if ($validator->fails()) {
            $response = [
                'message' => 'Error Validation',
                'errors'  => $validator->messages()
            ];
            $status = 422;
        } else {
            $incomingMessage = SuratMasuk::FindOrFail($suratId);
            $disposition = $incomingMessage->dispositions()->create([
                'kepada'  => $request->kepada,
                'catatan' => $request->catatan,
                'created_by' => $user->id
            ]);
            $incomingMessage->update([
                'status' => 'disposisi'
            ]);

            if ($tembusan = $request->tembusan) {
                $disposition->sections()->sync($tembusan);
            }

            $response = [
                'message' => 'Stored Successfully',
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
        $disposition = Disposition::with('sections')->where('id', $id)->first();

        if ($disposition) {
            $response = [
                'message' => 'fetched Successfully',
                'data' => $disposition
            ];
            $status = 200;
        } else {
            $response = [
                'message' => 'not found.',
            ];
            $status = 404;
        }

        return response()->json($response, $status);
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
        $validator = Validator::make($request->all(), [
            'kepada'  => 'required|numeric',
            'catatan' => 'required',
        ]);

        if ($validator->fails()) {
            $response = [
                'message' => 'Error Validation',
                'errors'  => $validator->messages()
            ];
            $status = 422;
        } else {
            $disposition = Disposition::FindOrFail($id);

            $disposition->update([
                'kepada'  => $request->kepada,
                'catatan' => $request->catatan,
                'updated_by' => $user->id
            ]);

            if ($tembusan = $request->tembusan) {
                $disposition->sections()->sync($tembusan);
            }

            $response = [
                'message' => 'updated Successfully',
            ];
            $status = 201;
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
        $disposition = Disposition::FindOrFail($id);

        if ($disposition) {
            $disposition->sections()->sync([]);
            $disposition->delete();
            $response = [
                'message' => 'deleted Successfully',
            ];
            $status = 200;
        } else {
            $response = [
                'message' => 'not found.',
            ];
            $status = 404;
        }

        return response()->json($response, $status);
    }

    public function tandaTerima($id, $response)
    {
        $disposition = Disposition::FindOrFail($id);

        $pdf = PDF::loadView('templates.disposition', [
            'disposition' => $disposition
        ]);

        if ($response == 'view') {
            return $pdf->stream();
        } else {
            return $pdf->download('disposisi_surat_masuk-' . $disposition->id . '.pdf');
        }
    }
}
