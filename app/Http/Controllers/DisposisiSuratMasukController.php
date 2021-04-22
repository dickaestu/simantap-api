<?php

namespace App\Http\Controllers;

use App\Models\Disposition;
use App\Models\History;
use App\Models\SuratMasuk;
use App\Models\SubBagian;
use App\Models\Notification;
use App\Models\User;

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
    public function index(Request $request)
    {
        $user = JWTAuth::user();
        $seq = $user->bagian->seq;

        //Check Bagian

        if ($request->keyword) {
            if ($seq == 2) {
                $dispositions = Disposition::with(['disposable.status_surat'])
                    ->whereHasMorph('disposable', [SuratMasuk::class], function ($item) use ($request) {
                        return $item->where('status', 2)->where(
                            'no_surat',
                            'like',
                            '%' . $request->keyword . '%'
                        )->orWhere(
                            'no_agenda',
                            'like',
                            '%' . $request->keyword . '%'
                        )->orWhere(
                            'perihal',
                            'like',
                            '%' . $request->keyword . '%'
                        )->orWhere(
                            'klasifikasi',
                            'like',
                            '%' . $request->keyword . '%'
                        );
                    })
                    ->get();
            } else {
                $dispositions = Disposition::with(['disposable.status_surat'])
                    ->whereHasMorph('disposable', [SuratMasuk::class], function ($item) use ($request) {
                        $item->where('status', 2)->where(
                            'no_surat',
                            'like',
                            '%' . $request->keyword . '%'
                        )->orWhere(
                            'no_agenda',
                            'like',
                            '%' . $request->keyword . '%'
                        )
                            ->orWhere(
                                'perihal',
                                'like',
                                '%' . $request->keyword . '%'
                            )
                            ->orWhere(
                                'klasifikasi',
                                'like',
                                '%' . $request->keyword . '%'
                            );
                    })
                    ->where('kepada', $user->sub_bagian_id)->get();
            }
        } else {
            if ($seq == 2) {
                $dispositions = Disposition::with(['disposable.status_surat'])
                    ->whereHasMorph('disposable', [SuratMasuk::class], function ($item) {
                        $item->where('status', 2);
                    })
                    ->get();
            } else {
                $dispositions = Disposition::with(['disposable.status_surat'])
                    ->whereHasMorph('disposable', [SuratMasuk::class], function ($item) {
                        $item->where('status', 2);
                    })
                    ->where('kepada', $user->sub_bagian_id)->get();
            }
        }

        $mappingDisposition = $dispositions->map(function ($item) {
            $item->disposable->file_path =
                'https://api.simantap.ngampooz.com/files/surat_masuk/' .  $item->disposable->file;
            return $item;
        });

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

        $incomingMessage = SuratMasuk::FindOrFail($suratId);


        $validator = Validator::make($request->all(), [
            'kepada'  => 'required|numeric',
            'isi_disposisi' => 'nullable',
        ]);

        if ($validator->fails()) {
            $response = [
                'message' => 'Error Validation',
                'errors'  => $validator->messages()
            ];
            $status = 422;
        } else {
            $response = null;
            $status = null;
            $incomingMessage->update(['status' => 2]);

            $disposition = $incomingMessage->dispositions()->create([
                'kepada'  => $request->kepada,
                'isi_disposisi' => $request->isi_disposisi,
                'created_by' => $user->id
            ]);

            //Get token firebase from user
            $body = $this->createStatus($disposition, $incomingMessage, $request->kepada, $seq, $user);
            $subBagian = SubBagian::FindOrFail($request->kepada);
            $firebaseData = [
                'token' => $subBagian->users()->where('roles_id', 4)->first()->device_token ?? null,
                'user_id' => $subBagian->users()->where('roles_id', 4)->first()->id,
                'body' => $body,
                'data' => [
                    'id' => $disposition->id,
                    'type' => 'disposition'
                ],
                'title' => '1 pekerjaan telah masuk.'
            ];
            if ($tembusan = $request->tembusan) {
                $disposition->sections()->sync($tembusan);
            }

            // Send Notification to Firebase
            if ($firebaseData['token']) {
                $notification = new Notification;
                $this->firebaseResponse = $notification->toSingleDevice($firebaseData, null, null);
                if ($firebaseData['token']) {
                    NotificationController::store($disposition, $firebaseData['user_id']);
                }
            }

            $response = [
                'message' => 'Stored Successfully',
                'firebase_response' => $this->firebaseResponse
            ];
            $status = 201;
        }

        return response()->json($response, $status);
    }

    function createStatus($disposition, $incomingMessage, $kepada, $seq, $user)
    {
        $subBagian = SubBagian::FindOrFail($kepada);

        $disposition->history()->create([
            'status' => 'Surat di disposisi oleh KASI ke ' . $subBagian->nama,
            'surat_id' => $incomingMessage->id,
            'tipe_surat' => "masuk"
        ]);
        $body = 'Disposisi surat, Nomor Agenda: ' . $incomingMessage->no_agenda;


        return $body;
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

        $disposition->history()->update([
            'status' => 'Surat di disposisi oleh KASI ke  ' . $subBagian->nama,
        ]);
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
        $subSections = SubBagian::select('id', 'nama', 'seq', 'bagian_id')->where('seq', 3)->get();


        $pdf = PDF::loadView('templates.disposition', [
            'disposition' => $disposition,
            'subSections' => $subSections,
        ]);


        if ($response == 'view') {
            return $pdf->stream();
        } else {
            return $pdf->download('disposisi_surat_masuk-' . $disposition->id . '.pdf');
        }
    }

    public function disposisi()
    {
        $user = JWTAuth::user();

        $subSections = SubBagian::with('jenis_bagian')->select('id', 'nama', 'seq', 'bagian_id')->where('seq', 3)->get();

        return response()->json([
            'message' => 'fetched all successfully.',
            'data' => $subSections
        ], 200);
    }

    public function history($suratId)
    {
        $user = JWTAuth::user();

        $data = History::with(['historable'])->where('surat_id', $suratId)->orderBy('created_at', 'asc')->get();

        $mappingData = $data->map(function ($item) {
            switch ($item->historable_type) {
                case "App\Models\SuratMasuk":
                    $item->url =
                        'https://api.simantap.ngampooz.com/files/surat_masuk/'  . $item->historable->file;
                    break;
                case "App\Models\Disposition":
                    $item->url = null;
                    break;
                case "App\Models\StaffminFile":
                    $item->url = 'https://api.simantap.ngampooz.com/files/staff_min/' . $item->historable->file;
                    break;
            }
            return $item;
        });

        return response()->json([
            'status' => 'Success',
            'data' => $mappingData
        ], 200);
    }
}
