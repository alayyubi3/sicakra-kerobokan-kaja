<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanKinerja;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->month;
        $thisYear = Carbon::now()->year;

        // Total staff aktif
        $totalStaffAktif = User::where('role', 'staff')->where('status_aktif', true)->count();

        // Laporan Hari Ini
        $totalLaporanHariIni = LaporanKinerja::whereDate('tanggal_laporan', $today->toDateString())->count();

        // Laporan Bulan Ini
        $totalLaporanBulanIni = LaporanKinerja::whereYear('tanggal_laporan', $thisYear)
            ->whereMonth('tanggal_laporan', $thisMonth)
            ->count();

        // Menunggu verifikasi
        $totalMenungguVerifikasi = LaporanKinerja::where('status_verifikasi', 'terkirim')->count();

        // Pegawai yang belum mengisi laporan hari ini
        $pegawaiBelumLaporHariIni = User::where('role', 'staff')
            ->where('status_aktif', true)
            ->whereDoesntHave('laporanKinerjas', function ($query) use ($today) {
                $query->whereDate('tanggal_laporan', $today->toDateString());
            })
            ->get();

        // Statistik per seksi/bidang bulan ini
        $statistikBidang = LaporanKinerja::join('users', 'laporan_kinerjas.user_id', '=', 'users.id')
            ->whereYear('laporan_kinerjas.tanggal_laporan', $thisYear)
            ->whereMonth('laporan_kinerjas.tanggal_laporan', $thisMonth)
            ->select('users.bidang_seksi', DB::raw('count(laporan_kinerjas.id) as total_laporan'))
            ->groupBy('users.bidang_seksi')
            ->get();

        // 5 Laporan terbaru yang menunggu verifikasi
        $laporanAntreanVerifikasi = LaporanKinerja::with('user')
            ->where('status_verifikasi', 'terkirim')
            ->orderBy('tanggal_laporan', 'desc')
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        // Ringkasan status verifikasi bulan ini
        $statusCounts = LaporanKinerja::whereYear('tanggal_laporan', $thisYear)
            ->whereMonth('tanggal_laporan', $thisMonth)
            ->select('status_verifikasi', DB::raw('count(*) as count'))
            ->groupBy('status_verifikasi')
            ->pluck('count', 'status_verifikasi')
            ->toArray();

        $disetujuiCount = $statusCounts['disetujui'] ?? 0;
        $revisiCount = $statusCounts['revisi'] ?? 0;
        $terkirimCount = $statusCounts['terkirim'] ?? 0;

        return view('admin.dashboard', compact(
            'totalStaffAktif',
            'totalLaporanHariIni',
            'totalLaporanBulanIni',
            'totalMenungguVerifikasi',
            'pegawaiBelumLaporHariIni',
            'statistikBidang',
            'laporanAntreanVerifikasi',
            'disetujuiCount',
            'revisiCount',
            'terkirimCount',
            'today',
            'thisMonth',
            'thisYear'
        ));
    }
}
