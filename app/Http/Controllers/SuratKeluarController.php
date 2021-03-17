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
        if ($seq === 2) {
            if ($request->keyword && $request->start_date == "" && $request->end_date == "") {
                $outcomeMessages = SuratKeluar::with(['user_created_by', 'updated_by', 'status_surat'])
                    ->where(
                        'no_surat',
                        'like',
                        '%' . $request->keyword . '%'
                    )
                    ->orWhere(
                        'perihal',
                        'like',
                        '%' . $request->keyword . '%'
                    )
                    ->where('status', '!=', 2)
                    ->orderBy('created_at', 'desc')->get();
            } else if (!$request->keyword && $request->start_date && $request->end_date) {
                $outcomeMessages = SuratKeluar::with(['user_created_by', 'updated_by', 'status_surat'])
                    ->where('status', '!=', 2)
                    ->whereBetween('tanggal_surat', [$request->start_date, $request->end_date])
                    ->orderBy('created_at', 'desc')->get();
            } else if ($request->keyword && $request->start_date && $request->end_date) {
                $outcomeMessages = SuratKeluar::with(['user_created_by', 'updated_by', 'status_surat'])
                    ->whereBetween('tanggal_surat', [$request->start_date, $request->end_date])
                    ->where(
                        'no_surat',
                        'like',
                        '%' . $request->keyword . '%'
                    )
                    ->orWhere(
                        'perihal',
                        'like',
                        '%' . $request->keyword . '%'
                    )
                    ->where('status', '!=', 2)
                    ->orderBy('created_at', 'desc')->get();
            } else {
                $outcomeMessages = SuratKeluar::with(['user_created_by', 'updated_by', 'status_surat'])
                    ->where('status', '!=', 2)
                    ->orderBy('created_at', 'desc')->get();
            }
            //Seq 3
        } else {
            if ($request->keyword && $request->start_date == "" && $request->end_date == "") {
                $outcomeMessages = SuratKeluar::with(['user_created_by', 'updated_by', 'status_surat'])
                    ->orWhere(
                        function ($query) use ($request) {
                            $query->where(
                                'no_surat',
                                'like',
                                '%' . $request->keyword . '%'
                            )
                                ->orWhere(
                                    'perihal',
                                    'like',
                                    '%' . $request->keyword . '%'
                                );
                        }
                    )
                    ->where('status', '!=', 2)
                    ->where('created_by', $user->id)
                    ->orderBy('created_at', 'desc')->get();
            } else if ($request->keyword && $request->start_date && $request->end_date) {
                $messages = SuratKeluar::with(['user_created_by', 'updated_by', 'status_surat'])
                    ->where('created_by', $user->id)
                    ->whereBetween('tanggal_surat', [$request->start_date, $request->end_date])
                    ->orWhere(
                        function ($query) use ($request) {
                            $query->where(
                                'no_surat',
                                'like',
                                '%' . $request->keyword . '%'
                            )
                                ->orWhere(
                                    'perihal',
                                    'like',
                                    '%' . $request->keyword . '%'
                                );
                        }
                    )
                    ->where('status', '!=', 2)
                    ->orderBy('created_at', 'desc')->get();
                $outcomeMessages = [];
                foreach ($messages as $message) {
                    if ($message->created_by == $user->id) {
                        $outcomeMessages[] = $message;
                    }
                }
            } else if (!$request->keyword && $request->start_date && $request->end_date) {
                $outcomeMessages = SuratKeluar::with(['user_created_by', 'updated_by', 'status_surat'])
                    ->whereBetween('tanggal_surat', [$request->start_date, $request->end_date])
                    ->where('status', '!=', 2)
                    ->where('created_by', $user->id)
                    ->orderBy('created_at', 'desc')->get();
            } else {
                $outcomeMessages = SuratKeluar::with(['user_created_by', 'updated_by', 'status_surat'])
                    ->where('status', '!=', 2)
                    ->where('created_by', $user->id)
                    ->orderBy('created_at', 'desc')->get();
            }
        }

        $mappingoutcomeMessages = collect($outcomeMessages)->map(function ($item) {
            $item->file_path =
                'https://api.simantap.ngampooz.com/files/surat_masuk/' . $item->file;
            return $item;
        });

        return response()->json([
            'message' => 'fetched successfully',
            'data' => $outcomeMessages
        ], 200);
    }

    public function suratKeluarSuccess(Request $request)
    {
        if ($request->keyword && $request->start_date == "" && $request->end_date == "") {
            $messages = SuratKeluar::with(['user_created_by', 'updated_by', 'status_surat'])
                ->where('status', 2)

                ->orWhere(
                    'no_surat',
                    'like',
                    '%' . $request->keyword . '%'
                )

                ->orWhere(
                    'perihal',
                    'like',
                    '%' . $request->keyword . '%'
                )
                ->orderBy('created_at', 'desc')->get();
            $outcomeMessages =  [];
            foreach ($messages as $message) {
                if ($message->status == 2) {
                    $outcomeMessages[] = $message;
                }
            }
        } else if (!$request->keyword && $request->start_date && $request->end_date) {
            $outcomeMessages = SuratKeluar::with(['user_created_by', 'updated_by', 'status_surat', 'staffmin_file'])
                ->where('status', 2)
                ->whereBetween('tanggal_surat', [$request->start_date, $request->end_date])
                ->orderBy('created_at', 'desc')->get();
        } else if ($request->keyword && $request->start_date && $request->end_date) {
            $messages = SuratKeluar::with(['user_created_by', 'updated_by', 'status_surat', 'staffmin_file'])
                ->where('status', 2)
                ->whereBetween('tanggal_surat', [$request->start_date, $request->end_date])
                ->where(
                    'no_surat',
                    'like',
                    '%' . $request->keyword . '%'
                )
                ->orWhere(
                    'perihal',
                    'like',
                    '%' . $request->keyword . '%'
                )
                ->orderBy('created_at', 'desc')->get();
            $outcomeMessages =  [];
            foreach ($messages as $message) {
                if ($message->status == 2) {
                    $outcomeMessages[] = $message;
                }
            }
        } else {
            $outcomeMessages = SuratKeluar::with(['user_created_by', 'updated_by', 'status_surat', 'staffmin_file'])
                ->where('status', 2)
                ->orderBy('created_at', 'desc')->get();
        }

        $mappingoutcomeMessages = collect($outcomeMessages)->map(function ($item) {
            $item->file_path =
                'https://api.simantap.ngampooz.com/files/surat_masuk/' . $item->file;
            if ($item->staffmin_file) {
                $item->staffmin_file->file_url =
                    'https://api.simantap.ngampooz.com/files/staff_min/' . $item->staffmin_file->file;
            }
            return $item;
        });

        return response()->json([
            'message' => 'fetched successfully',
            'data' => $outcomeMessages
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
            'perihal' => 'required|string|max:255',
            'pengolah' => 'required|string|max:255',
            'tujuan_surat' => 'required|string|max:255',
            'file.*' => 'nullable|file|mimes:csv,xlsx,xls,pdf,doc,docx|max:5000',

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


            $message = SuratKeluar::create([
                'no_surat' => $request->no_surat,
                'tanggal_surat' => $request->tanggal_surat,
                'perihal' => $request->perihal,
                'pengolah' => $request->pengolah,
                'tujuan_surat' => $request->tujuan_surat,
                'file' => $fileName ?? null,
                'created_by' => $user->id,
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
            'no_surat' => 'required|string|max:50|unique:surat_keluar,no_surat,' . $message->id,
            'tanggal_surat' => 'required|date',
            'perihal' => 'required|string|max:255',
            'pengolah' => 'required|string|max:255',
            'tujuan_surat' => 'required|string|max:255',
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
                'no_surat' => $request->no_surat,
                'tanggal_surat' => $request->tanggal_surat,
                'file' => $fileName ?? $message->file,
                'perihal' => $request->perihal,
                'pengolah' => $request->pengolah,
                'tujuan_surat' => $request->tujuan_surat,
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
