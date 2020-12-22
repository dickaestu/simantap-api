<?php

namespace App\Http\Controllers;

use App\Models\Disposition;
use App\Models\SuratMasuk;
use App\Models\SubBagian;

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
        $dispositions = Disposition::with(['disposable'])->where('kepada', $user->sub_bagian_id)->get();

        // $mappingDispositions = $dispositions->map(function ($item) {
        //     $item->tembusan = $item->sections()->get();

        //     return $item;
        // });

        return response()->json([
            'message' => 'fetched successfully',
            'data' => $dispositions
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
        $seq = $user->bagian->seq;


        // store untuk diposisi kasubag ke paur
        if ($seq == 3) {
            $name_sec = explode(" ", $user->bagian->nama)[1];
            $paur = SubBagian::where('seq', $seq + 1)->where('bagian_id', $user->bagian->bagian_id)
                ->where('nama', 'kaur ' . $name_sec)->first();

            $incomingMessage = SuratMasuk::FindOrFail($suratId);
            $disposition = $incomingMessage->dispositions()->create([
                'kepada'  => $paur->id,
                'isi_disposisi' => $request->isi_disposisi,
                'created_by' => $user->id
            ]);
            $incomingMessage->update(['status' => 4]);

            $this->createStatus($disposition, $incomingMessage, $paur->id, $seq, $user);

            // if ($tembusan = $request->tembusan) {
            //     $disposition->sections()->sync($tembusan);
            // }

            $response = [
                'message' => 'Stored Successfully',
            ];
            $status = 201;
        } elseif ($seq == 4) {
            // store untuk diposisi paur ke staff min
            $name_sec = explode(" ", $user->bagian->nama)[1];

            $staffmin = SubBagian::where('seq', $seq + 1)->where('bagian_id', $user->bagian->bagian_id)
                ->where('nama', 'staffmin ' . $name_sec)->first();

            $incomingMessage = SuratMasuk::FindOrFail($suratId);

            $disposition = $incomingMessage->dispositions()->create([
                'kepada'  => $staffmin->id,
                'catatan' => $request->catatan,
                'isi_disposisi' => $request->isi_disposisi,
                'created_by' => $user->id
            ]);

            $this->createStatus($disposition, $incomingMessage, $staffmin->id, $seq, $user);

            // if ($tembusan = $request->tembusan) {
            //     $disposition->sections()->sync($tembusan);
            // }

            $response = [
                'message' => 'Stored Successfully',
            ];
            $status = 201;
        } else {
            // store disposisi
            $validator = Validator::make($request->all(), [
                'kepada'  => 'required|numeric',
                'catatan' => 'nullable',
                'isi_disposisi' => 'nullable',
            ]);

            if ($validator->fails()) {
                $response = [
                    'message' => 'Error Validation',
                    'errors'  => $validator->messages()
                ];
                $status = 422;
            } else {
                $incomingMessage = SuratMasuk::FindOrFail($suratId);

                if ($seq == 1) {
                    $incomingMessage->update(['status' => 2]);
                } elseif ($seq == 2) {
                    $incomingMessage->update(['status' => 3]);
                }
                $disposition = $incomingMessage->dispositions()->create([
                    'kepada'  => $request->kepada,
                    'catatan' => $request->catatan,
                    'isi_disposisi' => $request->isi_disposisi,
                    'created_by' => $user->id
                ]);

                $this->createStatus($disposition, $incomingMessage, $request->kepada, $seq, $user);

                // if ($tembusan = $request->tembusan) {
                //     $disposition->sections()->sync($tembusan);
                // }

                $response = [
                    'message' => 'Stored Successfully',
                ];
                $status = 201;
            }
        }

        return response()->json($response, $status);
    }

    function createStatus($disposition, $incomingMessage, $kepada, $seq, $user)
    {
        $subBagian = SubBagian::FindOrFail($kepada);
        $bagian = $user->bagian;
        switch ($seq) {
            case 1:
                $disposition->history()->create([
                    'status' => 'Surat di disposisi oleh Karo ke ' . $subBagian->nama,
                    'surat_masuk_id' => $incomingMessage->id
                ]);
                break;

            default:
                $disposition->history()->create([
                    'status' => $bagian->nama  . ' mendisposisikan ke bagian ' . $subBagian->nama,
                    'surat_masuk_id' => $incomingMessage->id
                ]);
                break;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = JWTAuth::user();
        $seq = $user->bagian->seq;
        $disposition = Disposition::where('id', $id)->latest()->first();


        if ($disposition) {
            $allDisposition = Disposition::where('disposable_id', $disposition->disposable_id)->whereHas('subSector', function ($query) use ($seq) {
                $query->where('seq', '<=', $seq);
            })->get();

            $response = [
                'message' => 'fetched Successfully',
                'data' => $allDisposition
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
        $seq = $user->bagian->seq;
        $validator = Validator::make($request->all(), [
            'kepada'  => 'required|numeric',
            'catatan' => 'nullable',
            'isi_disposisi' => 'nullable',
        ]);

        if ($validator->fails()) {
            $response = [
                'message' => 'Error Validation',
                'errors'  => $validator->messages()
            ];
            $status = 422;
        } else {
            $disposition = Disposition::FindOrFail($id);

            if ($disposition->kepada != $request->kepada) {
                $this->updateStatus($disposition, $request->kepada, $seq, $user);
            }
            $disposition->update([
                'kepada'  => $request->kepada,
                'catatan' => $request->catatan,
                'isi_disposisi' => $request->isi_disposisi,
                'updated_by' => $user->id
            ]);


            // if ($tembusan = $request->tembusan) {
            //     $disposition->sections()->sync($tembusan);
            // }

            $response = [
                'message' => 'updated Successfully',
            ];
            $status = 201;
        }

        return response()->json($response, $status);
    }

    function updateStatus($disposition, $kepada, $seq, $user)
    {
        $subBagian = SubBagian::FindOrFail($kepada);
        $bagian = $user->bagian;
        switch ($seq) {
            case 1:
                $disposition->history()->update([
                    'status' => 'Surat di disposisi oleh Karo ke ' . $subBagian->nama,
                ]);
                break;

            default:
                $disposition->history()->update([
                    'status' => $bagian->nama  . 'mendisposisikan ke bagian ' . $subBagian->nama,
                ]);
                break;
        }
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
            // $disposition->sections()->sync([]);
            $disposition->history()->delete();
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

    public function cetakDisposisi($id, $response)
    {

        $disposition = Disposition::FindOrFail($id);
        $seq = $disposition->user_created_by->bagian->seq;
        $bagian_id = $disposition->user_created_by->bagian->bagian_id;

        if ($seq == 1) {
            $subSections = SubBagian::select('id', 'nama', 'seq', 'bagian_id')->where('seq', $seq + 1)->get();
        } else {
            $subSections = SubBagian::select('id', 'nama', 'seq', 'bagian_id')->where('seq', $seq + 1)->where('bagian_id', $bagian_id)->get();
        }


        if ($seq == 4) {
            $kasubag = Disposition::where('disposable_id', $disposition->disposable->id)
                ->whereHas('subSector', function ($item) use ($seq) {
                    return $item->where('seq', $seq);
                })
                ->first();
        }




        if ($seq == 1) {
            $pdf = PDF::loadView('templates.disposition', [
                'disposition' => $disposition,
                'subSections' => $subSections
            ]);
        } elseif ($seq == 2) {
            $pdf = PDF::loadView('templates.disposition-kasubag', [
                'disposition' => $disposition,
                'subSections' => $subSections
            ])->setPaper('a4', 'landscape');
        } elseif ($seq == 4) {
            $pdf = PDF::loadView('templates.disposition-paur', [
                'disposition' => $disposition,
                'subSections' => $subSections,
                'subbag' => explode(" ", $kasubag->user_created_by->bagian->nama)[1]
            ])->setPaper('a5');
        }

        if ($response == 'view') {
            return $pdf->stream();
        } else {
            return $pdf->download('disposisi_surat_masuk-' . $disposition->id . '.pdf');
        }
    }

    public function disposisi()
    {
        $user = JWTAuth::user();
        $seq = $user->bagian->seq;
        if ($seq == 1) {
            $subSections = SubBagian::with('jenis_bagian')->select('id', 'nama', 'seq', 'bagian_id')->where('seq', $seq + 1)->get();
        } else {
            if($user->bagian->bagian_id == 2){
                if($seq == 3) {
                    $bagian = explode(' ', $user->bagian->nama);
                    $subSection = SubBagian::with('jenis_bagian')->select('id', 'nama', 'seq', 'bagian_id')->where('seq', $seq + 1)->where('bagian_id', $user->bagian->bagian_id)->where('nama', 
                    'like', '%'.$bagian[1])->first();
                    $subSections = $subSection->users;
                } else {
                    $subSections = SubBagian::with('jenis_bagian')->select('id', 'nama', 'seq', 'bagian_id')->where('seq', $seq + 1)->where('bagian_id', $user->bagian->bagian_id)->get();
                }
            } else {
                if($seq == 4){
                    $bagian = explode(' ', $user->bagian->nama);
                    $subSection = SubBagian::with('jenis_bagian')->select('id', 'nama', 'seq', 'bagian_id')->where('seq', $seq + 1)->where('bagian_id', $user->bagian->bagian_id)->where('nama', 
                    'like', '%'.$bagian[1])->first();
                    $subSections = $subSection->users;
                } else {
                    $subSections = SubBagian::with('jenis_bagian')->select('id', 'nama', 'seq', 'bagian_id')->where('seq', $seq + 1)->where('bagian_id', $user->bagian->bagian_id)->get();
                }
            }
        }

        return response()->json([
            'message' => 'fetched all successfully.',
            'data' => $subSections
        ], 200);
    }
}
