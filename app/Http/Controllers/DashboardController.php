<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        if (!$request->start_date && !$request->end_date) {
            $end = Carbon::parse(now())->setTime(23, 59, 59);
            $start = Carbon::parse($end)->subMonth(5)->setTime(0, 0, 1);
        } else {
            $start = Carbon::parse($request->start_date)->setTime(0, 0, 1);
            $end = Carbon::parse($request->end_date)->setTime(23, 59, 59);
        }

        $user = JWTAuth::user();

        //Count Incoming message and Outcoming message, if seq > 1 specify from bagian
        if ($user->bagian->seq > 2) {
            $surat_masuk_on_process = SuratMasuk::where('status', '<', 2)->whereHas('dispositions', function ($item) use ($user) {
                $item->whereHas('subSector', function ($q) use ($user) {
                    $q->where('bagian_id', $user->bagian->bagian_id);
                });
            })->count();
            $surat_masuk_done = SuratMasuk::where('status', 2)->whereHas('dispositions', function ($item) use ($user) {
                $item->whereHas('subSector', function ($q) use ($user) {
                    $q->where('bagian_id', $user->bagian->bagian_id);
                });
            })->count();
            $surat_keluar_on_process = SuratKeluar::where('status', '<', 2)->whereHas('dispositions', function ($item) use ($user) {
                $item->whereHas('subSector', function ($q) use ($user) {
                    $q->where('bagian_id', $user->bagian->bagian_id);
                });
            })->count();
            $surat_keluar_done = SuratKeluar::where('status', 2)->whereHas('dispositions', function ($item) use ($user) {
                $item->whereHas('subSector', function ($q) use ($user) {
                    $q->where('bagian_id', $user->bagian->bagian_id);
                });
            })->count();
        } else {
            $surat_masuk_on_process = SuratMasuk::where('status', '<', 2)->count();
            $surat_masuk_done = SuratMasuk::where('status', 2)->count();
            $surat_keluar_on_process = SuratKeluar::where('status', '<', 2)->count();
            $surat_keluar_done = SuratKeluar::where('status', 2)->count();
        }

        //Check Months
        $diffInMonths = $start->diffInMonths($end) + 1;
        // dd($diffInMonths);

        //Generate Month List, example: 2021/01/12
        if ($diffInMonths == 0) {
            $monthListMasuk[] = (object) [
                'date' => Carbon::parse($start)->format('Y-m'),
                'value' => 0,
            ];
            $monthListKeluar[] = (object) [
                'date' => Carbon::parse($start)->format('Y-m'),
                'value' => 0,
            ];
        } else {
            for ($i = 0; $i < $diffInMonths; $i++) {
                $monthListMasuk[] = (object) [
                    'date' => Carbon::parse($start)->addMonth($i)->format('Y-m'),
                    'value' => 0,
                ];
                $monthListKeluar[] = (object) [
                    'date' => Carbon::parse($start)->addMonth($i)->format('Y-m'),
                    'value' => 0,
                ];
            }
        }

        //Count Surat Masuk from bagian_id in auth user if seq > 2
        switch ($user->bagian->seq) {
            case 2:
                // Count Surat Masuk
                foreach ($monthListMasuk as $month) {
                    $month->value = SuratMasuk::where('created_at', 'like', $month->date . '%')->count();
                }
                //Count Surat Keluar
                foreach ($monthListKeluar as $month) {
                    $month->value = SuratKeluar::where('created_at', 'like', $month->date . '%')->count();
                }
                break;

            default:
                // Count Surat Masuk
                foreach ($monthListMasuk as $month) {
                    $month->value = SuratMasuk::where('created_at', 'like', $month->date . '%')->whereHas('dispositions', function ($item) use ($user) {
                        $item->whereHas('subSector', function ($q) use ($user) {
                            $q->where('bagian_id', $user->bagian->bagian_id);
                        });
                    })->count();
                }
                //Count Surat Keluar
                foreach ($monthListKeluar as $month) {
                    $month->value = SuratKeluar::where('created_at', 'like', $month->date . '%')->whereHas('dispositions', function ($item) use ($user) {
                        $item->whereHas('subSector', function ($q) use ($user) {
                            $q->where('bagian_id', $user->bagian->bagian_id);
                        });
                    })->count();
                }
                break;
        }


        return response()->json([
            'message' => 'fetched all success.',
            'surat_masuk_on_process' => $surat_masuk_on_process,
            'surat_masuk_done' => $surat_masuk_done,
            'surat_keluar_on_process' => $surat_keluar_on_process,
            'surat_keluar_done' => $surat_keluar_done,
            'grafik_surat_masuk' => $monthListMasuk,
            'grafik_surat_keluar' => $monthListKeluar,
        ], 200);
    }
}
