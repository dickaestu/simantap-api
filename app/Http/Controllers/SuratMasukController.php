<?php

namespace App\Http\Controllers;

use App\Models\Bagian;
use App\Models\StaffminFile;
use App\Models\User;
use App\Models\Notification;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Tymon\JWTAuth\Facades\JWTAuth;
use PDF;

use App\Models\SuratMasuk;
use Carbon\Carbon;

class SuratMasukController extends Controller
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
                $incomingMessages = SuratMasuk::with(['user_created_by', 'updated_by', 'status_surat'])
                    ->where(
                        'no_agenda',
                        'like',
                        '%' . $request->keyword . '%'
                    )
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
                    ->orWhere(
                        'klasifikasi',
                        'like',
                        '%' . $request->keyword . '%'
                    )
                    ->where('status', '!=', 2)
                    ->orderBy('created_at', 'desc')->get();
            } else if (!$request->keyword && $request->start_date && $request->end_date) {
                $incomingMessages = SuratMasuk::with(['user_created_by', 'updated_by', 'status_surat'])
                    ->where('status', '!=', 2)
                    ->whereBetween('tanggal_surat', [$request->start_date, $request->end_date])
                    ->orderBy('created_at', 'desc')->get();
            } else if ($request->keyword && $request->start_date && $request->end_date) {
                $incomingMessages = SuratMasuk::with(['user_created_by', 'updated_by', 'status_surat'])
                    ->whereBetween('tanggal_surat', [$request->start_date, $request->end_date])
                    ->where(
                        'no_agenda',
                        'like',
                        '%' . $request->keyword . '%'
                    )
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
                    ->orWhere(
                        'klasifikasi',
                        'like',
                        '%' . $request->keyword . '%'
                    )
                    ->where('status', '!=', 2)
                    ->orderBy('created_at', 'desc')->get();
            } else {
                $incomingMessages = SuratMasuk::with(['user_created_by', 'updated_by', 'status_surat'])
                    ->where('status', '!=', 2)
                    ->orderBy('created_at', 'desc')->get();
            }
            //Seq 3
        } else {
            if ($request->keyword && $request->start_date == "" && $request->end_date == "") {
                $incomingMessages = SuratMasuk::with(['user_created_by', 'updated_by', 'status_surat'])
                    ->orWhere(
                        function ($query) use ($request) {
                            $query->where(
                                'no_agenda',
                                'like',
                                '%' . $request->keyword . '%'
                            )
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
                                ->orWhere(
                                    'klasifikasi',
                                    'like',
                                    '%' . $request->keyword . '%'
                                );
                        }
                    )
                    ->where('status', '!=', 2)
                    ->where('created_by', $user->id)
                    ->orderBy('created_at', 'desc')->get();
            } else if ($request->keyword && $request->start_date && $request->end_date) {
                $messages = SuratMasuk::with(['user_created_by', 'updated_by', 'status_surat'])
                    ->where('created_by', $user->id)
                    ->whereBetween('tanggal_surat', [$request->start_date, $request->end_date])
                    ->orWhere(
                        function ($query) use ($request) {
                            $query->where(
                                'no_agenda',
                                'like',
                                '%' . $request->keyword . '%'
                            )
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
                                ->orWhere(
                                    'klasifikasi',
                                    'like',
                                    '%' . $request->keyword . '%'
                                );
                        }
                    )
                    ->where('status', '!=', 2)
                    ->orderBy('created_at', 'desc')->get();
                $incomingMessages = [];
                foreach ($messages as $message) {
                    if ($message->created_by == $user->id) {
                        $incomingMessages[] = $message;
                    }
                }
            } else if (!$request->keyword && $request->start_date && $request->end_date) {
                $incomingMessages = SuratMasuk::with(['user_created_by', 'updated_by', 'status_surat'])
                    ->whereBetween('tanggal_surat', [$request->start_date, $request->end_date])
                    ->where('status', '!=', 2)
                    ->where('created_by', $user->id)
                    ->orderBy('created_at', 'desc')->get();
            } else {
                $incomingMessages = SuratMasuk::with(['user_created_by', 'updated_by', 'status_surat'])
                    ->where('status', '!=', 2)
                    ->where('created_by', $user->id)
                    ->orderBy('created_at', 'desc')->get();
            }
        }

        $mappingIncomingMessages = collect($incomingMessages)->map(function ($item) {
            $item->file_path =
                'https://api.simantap.ngampooz.com/files/surat_masuk/' . $item->file;
            return $item;
        });

        return response()->json([
            'message' => 'fetched successfully',
            'data' => $incomingMessages
        ], 200);
    }

    public function suratMasukSuccess(Request $request)
    {
        if ($request->keyword && $request->start_date == "" && $request->end_date == "") {
            $messages = SuratMasuk::with(['user_created_by', 'updated_by', 'status_surat'])
                ->where('status', 2)
                ->orWhere(
                    'no_agenda',
                    'like',
                    '%' . $request->keyword . '%'
                )
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
                ->orWhere(
                    'klasifikasi',
                    'like',
                    '%' . $request->keyword . '%'
                )
                ->orderBy('created_at', 'desc')->get();
            $incomingMessages =  [];
            foreach ($messages as $message) {
                if ($message->status == 2) {
                    $incomingMessages[] = $message;
                }
            }
        } else if (!$request->keyword && $request->start_date && $request->end_date) {
            $incomingMessages = SuratMasuk::with(['user_created_by', 'updated_by', 'status_surat', 'staffmin_file'])
                ->where('status', 2)
                ->whereBetween('tanggal_surat', [$request->start_date, $request->end_date])
                ->orderBy('created_at', 'desc')->get();
        } else if ($request->keyword && $request->start_date && $request->end_date) {
            $messages = SuratMasuk::with(['user_created_by', 'updated_by', 'status_surat', 'staffmin_file'])
                ->where('status', 2)
                ->whereBetween('tanggal_surat', [$request->start_date, $request->end_date])
                ->where(
                    'no_agenda',
                    'like',
                    '%' . $request->keyword . '%'
                )
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
                ->orWhere(
                    'klasifikasi',
                    'like',
                    '%' . $request->keyword . '%'
                )
                ->orderBy('created_at', 'desc')->get();
            $incomingMessages =  [];
            foreach ($messages as $message) {
                if ($message->status == 2) {
                    $incomingMessages[] = $message;
                }
            }
        } else {
            $incomingMessages = SuratMasuk::with(['user_created_by', 'updated_by', 'status_surat', 'staffmin_file'])
                ->where('status', 2)
                ->orderBy('created_at', 'desc')->get();
        }

        $mappingIncomingMessages = collect($incomingMessages)->map(function ($item) {
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
            'no_agenda' => 'required|string|max:50|unique:surat_masuk',
            'tanggal_surat' => 'required|date',
            'tanggal_terima' => 'required|date',
            'perihal' => 'required|string|max:255',
            'file.*' => 'nullable|file|mimes:csv,xlsx,xls,pdf,doc,docx|max:5000',
            'klasifikasi' => 'required',
            'status_type' => 'required|in:biasa,kilat'
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

                $file->move('files/surat_masuk', $fileName);
            }


            $surat = $this->generateSurat($request->klasifikasi);

            $message = SuratMasuk::create([
                'no_surat' => $surat,
                'no_agenda' => $request->no_agenda,
                'tanggal_surat' => $request->tanggal_surat,
                'tanggal_terima' => $request->tanggal_terima,
                'perihal' => $request->perihal,
                'file' => $fileName ?? null,
                'created_by' => $user->id,
                'klasifikasi' => $request->klasifikasi,
                'status' => 1,
                'status_type' => $request->status_type
            ]);

            $message->history()->create([
                'status' => 'Surat Masuk dibuat ' . $user->name,
                'surat_id' => $message->id,
                'tipe_surat' => 'masuk'
            ]);

            $userReceiveNotif = User::where('roles_id', 4)->where('sub_bagian_id', 9)->first();
            //Set FirebaseData for Send Notification
            $firebaseData = [
                'token' => $userReceiveNotif->device_token ?? null,
                'user_id' => $userReceiveNotif->id,
                'body' => 'Terdapat surat masuk dengan nomor surat :' . $message->no_surat,
                'data' => [
                    'id' => $message->id,
                    'type' => 'surat_masuk'
                ],
                'title' => 'Surat masuk telah diterima'
            ];

            $notification = new Notification;
            $firebaseResponse = $notification->toSingleDevice($firebaseData, null, null);

            if ($firebaseData['token']) {
                NotificationController::store($message, $firebaseData['user_id']);
            }
            $response = [
                'message' => 'stored successfully',
                'firebase_response' => $firebaseResponse
            ];
            $status = 201;
        }

        return response()->json($response, $status);
    }

    /**
     * Auto Generate Nomor Agenda.
     *
     * @param  string  $klasifikasi
     * @return \Illuminate\Http\Response
     */
    function generateSurat($classification)
    {
        $code = strtoupper($classification[0]);
        $message = SuratMasuk::where('klasifikasi', $classification)->latest()->first();
        if ($message) {
            $explode = explode('-', $message->no_surat);
            $no_surat = $code . "-" . sprintf('%05d', ($explode[1] + 1));
        } else {
            $no_surat = $code . "-" . sprintf('%05d', 1);
        }

        return $no_surat;
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
            'no_agenda' => 'required|string|max:50|unique:surat_masuk,no_agenda,' . $message->id,
            'tanggal_surat' => 'required|date',
            'tanggal_terima' => 'required|date',
            'perihal' => 'required|string|max:255',
            'file.*' => 'file|mimes:csv,xlsx,xls,pdf,doc,docx|max:5000',
            'status_type' => 'required|in:biasa,kilat'


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
                'tanggal_surat' => $request->tanggal_surat,
                'tanggal_terima' => $request->tanggal_terima,
                'file' => $fileName ?? $message->file,
                'perihal' => $request->perihal,
                'updated_by' => $user->id,
                'status_type' => $request->status_type
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
