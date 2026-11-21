<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\LaporanKinerja;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $now = Carbon::now();

        $bulan = (int) $request->get('bulan', $now->month);
        $tahun = (int) $request->get('tahun', $now->year);

        $queryBulanIni = LaporanKinerja::where('user_id', $user->id)
            ->whereYear('tanggal_laporan', $tahun)
            ->whereMonth('tanggal_laporan', $bulan);

        $totalLaporanBulanIni = (clone $queryBulanIni)->count();
        $totalDisetujui = (clone $queryBulanIni)->where('status_verifikasi', 'disetujui')->count();
        $totalTerkirim = (clone $queryBulanIni)->where('status_verifikasi', 'terkirim')->count();
        $totalRevisi = (clone $queryBulanIni)->where('status_verifikasi', 'revisi')->count();

        // Total seluruh laporan
        $totalSemuaLaporan = LaporanKinerja::where('user_id', $user->id)->count();

        // Laporan butuh revisi
        $laporanPerluRevisi = LaporanKinerja::where('user_id', $user->id)
            ->where('status_verifikasi', 'revisi')
            ->orderBy('tanggal_laporan', 'desc')
            ->take(5)
            ->get();

        // 5 Laporan Terkini
        $laporanTerbaru = LaporanKinerja::where('user_id', $user->id)
            ->orderBy('tanggal_laporan', 'desc')
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        // Laporan hari ini
        $hariIniCount = LaporanKinerja::where('user_id', $user->id)
            ->whereDate('tanggal_laporan', $now->toDateString())
            ->count();

        return view('staff.dashboard', compact(
            'user',
            'bulan',
            'tahun',
            'totalLaporanBulanIni',
            'totalDisetujui',
            'totalTerkirim',
            'totalRevisi',
            'totalSemuaLaporan',
            'laporanPerluRevisi',
            'laporanTerbaru',
            'hariIniCount'
        ));
    }
}
