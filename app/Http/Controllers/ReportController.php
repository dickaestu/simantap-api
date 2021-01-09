<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function suratMasuk(Request $request)
    {
        if ($request->keyword) {
            $incomingMessages = SuratMasuk::with(['created_by', 'updated_by', 'status_surat'])
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
            $incomingMessages = SuratMasuk::with(['created_by', 'updated_by', 'status_surat'])
                ->where('status', 6)

                ->whereBetween('tanggal_surat', [$request->start_date, $request->end_date])
                ->orderBy('created_at', 'desc')->get();
        } else if ($request->keyword && $request->start_date && $request->end_date) {
            $incomingMessages = SuratMasuk::with(['created_by', 'updated_by', 'status_surat'])
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
            $incomingMessages = SuratMasuk::with(['created_by', 'updated_by', 'status_surat'])
                ->where('status', 6)
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

    public function suratKeluar(Request $request)
    {
        if ($request->keyword) {
            $data = SuratKeluar::with(['created_by', 'updated_by', 'status_surat', 'bagian'])
                ->where('status', 3)
                ->where(
                    'no_surat',
                    'like',
                    '%' . $request->keyword . '%'
                )
                ->orWhere(
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
                )->orderBy('created_at', 'desc')->get();
        } else if ($request->start_date && $request->end_date) {
            $data = SuratKeluar::with(['created_by', 'updated_by', 'status_surat', 'bagian'])
                ->where('status', 3)
                ->whereBetween('tanggal_surat', [$request->start_date, $request->end_date])
                ->orderBy('created_at', 'desc')->get();
        } else if ($request->keyword && $request->start_date && $request->end_date) {
            $data = SuratKeluar::with(['created_by', 'updated_by', 'status_surat', 'bagian'])
                ->where('status', 3)
                ->whereBetween('tanggal_surat', [$request->start_date, $request->end_date])
                ->where(
                    'no_surat',
                    'like',
                    '%' . $request->keyword . '%'
                )
                ->orWhere(
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
                )
                ->orderBy('created_at', 'desc')->get();
        } else {
            $data = SuratKeluar::with(['created_by', 'updated_by', 'status_surat', 'bagian'])
                ->where('status', 3)
                ->orderBy('created_at', 'desc')->get();
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
}
