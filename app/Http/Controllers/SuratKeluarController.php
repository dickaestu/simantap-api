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
                $data = SuratKeluar::with(['user_created_by', 'updated_by', 'status_surat', 'bagian'])
                    ->whereBetween('tanggal_surat', [$request->start_date, $request->end_date])
                    ->where('status', 2)
                    ->where('bagian_id', $user->bagian->bagian_id)
                    ->orderBy('created_at', 'desc')->get();
            } else if ($request->keyword && $request->start_date && $request->end_date) {
                $data = SuratKeluar::with(['user_created_by', 'updated_by', 'status_surat', 'bagian'])
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
                $data = SuratKeluar::with(['user_created_by', 'updated_by', 'status_surat', 'bagian'])
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
            'no_agenda' => 'required|string|max:50|unique:surat_keluar',
            'tanggal_surat' => 'required|date',
            'tanggal_terima' => 'required|date',
            'perihal' => 'required|string|max:255',
            'file.*' => 'nullable|file|mimes:csv,xlsx,xls,pdf,doc,docx|max:5000',
            'klasifikasi' => 'required'
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

                $file->move('files/surat_keluar', $fileName);
            }

            $surat = $this->generateSurat($request->klasifikasi);

            $message = SuratKeluar::create([
                'no_surat' => $surat,
                'no_agenda' => $request->no_agenda,
                'tanggal_surat' => $request->tanggal_surat,
                'tanggal_terima' => $request->tanggal_terima,
                'perihal' => $request->perihal,
                'file' => $fileName ?? null,
                'created_by' => $user->id,
                'klasifikasi' => $request->klasifikasi,
                'status' => 1
            ]);

            $message->history()->create([
                'status' => 'Surat Keluar dibuat ' . $user->name,
                'surat_id' => $message->id,
                'tipe_surat' => "keluar"
            ]);

            // $userReceiveNotif = User::where('roles_id', 2)->where('sub_bagian_id', 1)->first();
            // //Set FirebaseData for Send Notification
            // $firebaseData = [
            //     'token' => $userReceiveNotif->device_token ?? null,
            //     'user_id' => $userReceiveNotif->id,
            //     'body' => 'Terdapat surat masuk dengan nomor surat :' . $message->no_surat,
            //     'data' => [
            //         'id' => $message->id,
            //         'type' => 'surat_masuk'
            //     ],
            //     'title' => 'Surat masuk telah diterima'
            // ];

            // $notification = new Notification;
            // $notification->toSingleDevice($firebaseData, null, null);

            // if ($firebaseData['token']) {
            //     NotificationController::store($message, $firebaseData['user_id']);
            // }
            $response = [
                'message' => 'stored successfully'
            ];
            $status = 201;
        }

        return response()->json($response, $status);
    }

    function generateSurat($classification)
    {
        $code = strtoupper($classification[0]);
        $message = SuratKeluar::where('klasifikasi', $classification)->latest()->first();
        if ($message) {
            $explode = explode('-', $message->no_surat);
            $no_surat = $code . "-" . sprintf('%05d', ($explode[1] + 1));
        } else {
            $no_surat = $code . "-" . sprintf('%05d', 1);
        }

        return $no_surat;
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
        $message = SuratKeluar::FindOrFail($id);
        $validator = Validator::make($request->all(), [
            'no_agenda' => 'required|string|max:50|unique:surat_keluar,no_agenda,' . $message->id,
            'tanggal_surat' => 'required|date',
            'tanggal_terima' => 'required|date',
            'perihal' => 'required|string|max:255',
            'file.*' => 'file|mimes:csv,xlsx,xls,pdf,doc,docx|max:5000',

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
                File::delete('files/surat_keluar/' . $message->file);
                $file->move('files/surat_keluar/', $fileName);
            }

            $message->update([
                'no_agenda' => $request->no_agenda,
                'tanggal_surat' => $request->tanggal_surat,
                'tanggal_terima' => $request->tanggal_terima,
                'file' => $fileName ?? $message->file,
                'perihal' => $request->perihal,
                'updated_by' => $user->id,
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
