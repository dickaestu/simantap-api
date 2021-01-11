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
        $start = $request->start_date . ' 00:00:01';
        $end = $request->end_date . ' 23:59:00';

        $DeferenceInDays = Carbon::parse($start)->diffInDays($end);

        $user = JWTAuth::user();
        $seq = $user->bagian->seq;

        $surat_masuk_on_process = SuratMasuk::where('status', '<', 6)->count();
        $surat_masuk_done = SuratMasuk::where('status', 6)->count();
        $surat_keluar_on_process = SuratKeluar::where('status', '<', 3)->count();
        $surat_keluar_done = SuratKeluar::where('status', 3)->count();

        $surat_masuk_monthly = SuratMasuk::select(
            DB::raw('date(created_at) as created_date'),
            DB::raw('sum(id) as total_surat')
        )
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('created_at')->get()->pluck('total_surat', 'created_date')->all();

        $surat_keluar_monthly = SuratKeluar::select(
            DB::raw('date(created_at) as created_date'),
            DB::raw('sum(id) as total_surat')
        )
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('created_at')->get()->pluck('total_surat', 'created_date')->all();
        for ($i = Carbon::parse($start); $i <= Carbon::parse($end); $i->addDays()) {
            if (array_key_exists($i->format('Y-m'), $surat_masuk_monthly)) {

                $data[$i->format('Y-m')] = $surat_masuk_monthly[$i->format('Y-m')];
            } else {

                $data[$i->format('Y-m')] = 0;
            }
        }

        //MonthList Surat Masuk
        foreach ($data as $key => $dt) {
            $obj = (object) array('date' => $key, 'value' => $dt);
            $monthListSuratMasuk[] = $obj;
        }

        //MonthList Surat Keluar
        foreach ($data as $key => $dt) {
            $obj = (object) array('date' => $key, 'value' => $dt);
            $monthListSuratKeluar[] = $obj;
        }

        //Count Surat Masuk
        foreach ($surat_masuk_monthly as $key => $value) {
            foreach ($monthListSuratMasuk as $month) {
                if ($month->date == Carbon::parse($key)->format('Y-m')) {
                    $month->value += $value;
                }
            }
        }

        //Count Surat Keluar
        foreach ($surat_keluar_monthly as $key => $value) {
            foreach ($monthListSuratKeluar as $month) {
                if ($month->date == Carbon::parse($key)->format('Y-m')) {
                    $month->value += $value;
                }
            }
        }

        return response()->json([
            'message' => 'fetched all success.',
            'surat_masuk_on_process' => $surat_masuk_on_process,
            'surat_masuk_done' => $surat_masuk_done,
            'surat_keluar_on_process' => $surat_keluar_on_process,
            'surat_keluar_done' => $surat_keluar_done,
            'grafik_surat_masuk' => $monthListSuratMasuk,
            'grafik_surat_keluar' => $monthListSuratKeluar,
        ], 200);
    }
}
