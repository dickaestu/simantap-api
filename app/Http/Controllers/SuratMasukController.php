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
        if ($request->keyword) {
            $incomingMessages = SuratMasuk::with(['created_by', 'updated_by', 'status_surat'])
                ->where('status', '!=', 6)

                ->where(
                    'no_surat',
                    'like',
                    '%' . $request->keyword . '%'
                )
                ->orWhere(
                    'no_agenda',
                    'like',
                    '%' . $request->keyword . '%'
                )
                ->orWhere(
                    'sumber_surat',
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
        } else if ($request->start_date && $request->end_date) {
            $incomingMessages = SuratMasuk::with(['created_by', 'updated_by', 'status_surat'])
                ->where('status', '!=', 6)

                ->whereBetween('tanggal_surat', [$request->start_date, $request->end_date])
                ->orderBy('created_at', 'desc')->get();
        } else if ($request->keyword && $request->start_date && $request->end_date) {
            $incomingMessages = SuratMasuk::with(['created_by', 'updated_by', 'status_surat'])
                ->where('status', '!=', 6)

                ->whereBetween('tanggal_surat', [$request->start_date, $request->end_date])
                ->where(
                    'no_surat',
                    'like',
                    '%' . $request->keyword . '%'
                )
                ->orWhere(
                    'no_agenda',
                    'like',
                    '%' . $request->keyword . '%'
                )
                ->orWhere(
                    'sumber_surat',
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
        } else {
            $incomingMessages = SuratMasuk::with(['created_by', 'updated_by', 'status_surat'])
                ->where('status', '!=', 6)
                ->orderBy('created_at', 'desc')->get();
        }

        $mappingIncomingMessages = $incomingMessages->map(function ($item) {
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
        if ($request->keyword) {
            $incomingMessages = SuratMasuk::with(['created_by', 'updated_by', 'status_surat', 'staffmin_file'])
                ->where('status', 6)
                ->where(
                    'no_surat',
                    'like',
                    '%' . $request->keyword . '%'
                )
                ->orWhere(
                    'no_agenda',
                    'like',
                    '%' . $request->keyword . '%'
                )
                ->orWhere(
                    'sumber_surat',
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
        } else if ($request->start_date && $request->end_date) {
            $incomingMessages = SuratMasuk::with(['created_by', 'updated_by', 'status_surat', 'staffmin_file'])
                ->where('status', 6)
                ->whereBetween('tanggal_surat', [$request->start_date, $request->end_date])
                ->orderBy('created_at', 'desc')->get();
        } else if ($request->keyword && $request->start_date && $request->end_date) {
            $incomingMessages = SuratMasuk::with(['created_by', 'updated_by', 'status_surat', 'staffmin_file'])
                ->where('status', 6)
                ->whereBetween('tanggal_surat', [$request->start_date, $request->end_date])
                ->where(
                    'no_surat',
                    'like',
                    '%' . $request->keyword . '%'
                )
                ->orWhere(
                    'no_agenda',
                    'like',
                    '%' . $request->keyword . '%'
                )
                ->orWhere(
                    'sumber_surat',
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
        } else {
            $incomingMessages = SuratMasuk::with(['created_by', 'updated_by', 'status_surat', 'staffmin_file'])
                ->where('status', 6)
                ->orderBy('created_at', 'desc')->get();
        }



        $mappingIncomingMessages = $incomingMessages->map(function ($item) {
            $item->file_path =
                'https://api.simantap.ngampooz.com/files/surat_masuk/' . $item->file;
            $item->staffmin_file->file_url =
                'https://api.simantap.ngampooz.com/files/staff_min/' . $item->staffmin_file->file;

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
            'no_surat' => 'required|string|max:50|unique:surat_masuk',
            'tanggal_surat' => 'required|date',
            'tanggal_terima' => 'required|date',
            'sumber_surat' => 'required|string|max:255',
            'perihal' => 'required|string|max:255',
            'file.*' => 'required|file|mimes:csv,xlsx,xls,pdf,doc,docx|max:5000',
            'keterangan' => 'nullable',
            'klasifikasi' => 'required'
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

            $agenda = $this->generateAgenda($request->klasifikasi);

            $message = SuratMasuk::create([
                'no_agenda' => $agenda,
                'no_surat' => $request->no_surat,
                'tanggal_surat' => $request->tanggal_surat,
                'tanggal_terima' => $request->tanggal_terima,
                'sumber_surat' => $request->sumber_surat,
                'perihal' => $request->perihal,
                'file' => $fileName,
                'keterangan' => $request->keterangan,
                'created_by' => $user->id,
                'klasifikasi' => $request->klasifikasi,
                'status' => 1
            ]);

            $message->history()->create([
                'status' => 'Surat Masuk dibuat Karo.',
                'surat_masuk_id' => $message->id
            ]);
            
            $userReceiveNotif = User::where('roles_id', 2)->where('sub_bagian_id', 1)->first();
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
            $notification->toSingleDevice($firebaseData, null, null);
        
            if($firebaseData['token']){
                NotificationController::store($message, $firebaseData['user_id']);
            }
            $response = [
                'message' => 'stored successfully'
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
    function generateAgenda($classification)
    {
        $code = strtoupper($classification[0]);
        $message = SuratMasuk::where('klasifikasi', $classification)->latest()->first();
        if ($message) {
            $explode = explode('-', $message->no_agenda);
            $no_agenda = $code . "-" . sprintf('%05d', ($explode[1] + 1));
        } else {
            $no_agenda = $code . "-" . sprintf('%05d', 1);
        }

        return $no_agenda;
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
            'no_surat' => 'required|string|max:50|unique:surat_masuk,no_surat,' . $message->id,
            'tanggal_surat' => 'required|date',
            'tanggal_terima' => 'required|date',
            'sumber_surat' => 'required|string|max:255',
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
                'no_surat' => $request->no_surat,
                'tanggal_surat' => $request->tanggal_surat,
                'tanggal_terima' => $request->tanggal_terima,
                'sumber_surat' => $request->sumber_surat,
                'file' => $fileName ?? $message->file,
                'perihal' => $request->perihal,
                'keterangan' => $request->keterangan,
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
