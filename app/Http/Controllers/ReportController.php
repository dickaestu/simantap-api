<?php

namespace App\Http\Controllers;

use App\Models\Disposition;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;

use Tymon\JWTAuth\Facades\JWTAuth;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function suratMasuk(Request $request)
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

    public function suratKeluar(Request $request)
    {
        $user = JWTAuth::user();
        $seq = $user->bagian->seq;
        if ($seq === 2) {
            if ($request->keyword && $request->start_date == "" && $request->end_date == "") {
                $outcomeMessages = SuratKeluar::with(['user_created_by', 'updated_by', 'status_surat'])
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
                    ->where('status', 1)
                    ->orderBy('created_at', 'desc')->get();
            } else if (!$request->keyword && $request->start_date && $request->end_date) {
                $outcomeMessages = SuratKeluar::with(['user_created_by', 'updated_by', 'status_surat'])
                    ->where('status', 1)
                    ->whereBetween('tanggal_surat', [$request->start_date, $request->end_date])
                    ->orderBy('created_at', 'desc')->get();
            } else if ($request->keyword && $request->start_date && $request->end_date) {
                $outcomeMessages = SuratKeluar::with(['user_created_by', 'updated_by', 'status_surat'])
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
                    ->where('status', 1)
                    ->orderBy('created_at', 'desc')->get();
            } else {
                $outcomeMessages = SuratKeluar::with(['user_created_by', 'updated_by', 'status_surat'])
                    ->where('status', 1)
                    ->orderBy('created_at', 'desc')->get();
            }
            //Seq 3
        } else {
            if ($request->keyword && $request->start_date == "" && $request->end_date == "") {
                $outcomeMessages = SuratKeluar::with(['user_created_by', 'updated_by', 'status_surat'])
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
                    ->where('status', 1)
                    ->where('created_by', $user->id)
                    ->orderBy('created_at', 'desc')->get();
            } else if ($request->keyword && $request->start_date && $request->end_date) {
                $messages = SuratKeluar::with(['user_created_by', 'updated_by', 'status_surat'])
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
                    ->where('status', 1)
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
                    ->where('status', 1)
                    ->where('created_by', $user->id)
                    ->orderBy('created_at', 'desc')->get();
            } else {
                $outcomeMessages = SuratKeluar::with(['user_created_by', 'updated_by', 'status_surat'])
                    ->where('status', 1)
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
}
