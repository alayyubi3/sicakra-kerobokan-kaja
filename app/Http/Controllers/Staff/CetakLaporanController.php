<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\KopLaporan;
use App\Models\LaporanKinerja;
use App\Services\LaporanPdfService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CetakLaporanController extends Controller
{
    public function __construct(
        protected LaporanPdfService $pdfService
    ) {}

    public function index(Request $request): View
    {
        $user = Auth::user();
        $now = Carbon::now();

        $bulan = (int) $request->get('bulan', $now->month);
        $tahun = (int) $request->get('tahun', $now->year);
        $mode = $request->get('mode', 'bulanan'); // bulanan / rentang
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $hanyaDisetujui = $request->boolean('hanya_disetujui', false);

        $query = LaporanKinerja::where('user_id', $user->id);

        if ($mode === 'rentang' && $startDate && $endDate) {
            $query->whereBetween('tanggal_laporan', [$startDate, $endDate]);
        } else {
            $query->whereYear('tanggal_laporan', $tahun)
                ->whereMonth('tanggal_laporan', $bulan);
        }

        if ($hanyaDisetujui) {
            $query->where('status_verifikasi', 'disetujui');
        }

        $laporanList = $query->orderBy('tanggal_laporan', 'asc')
            ->orderBy('jam_mulai', 'asc')
            ->get();

        $kop = KopLaporan::getActive();

        return view('staff.cetak.index', compact(
            'user',
            'bulan',
            'tahun',
            'mode',
            'startDate',
            'endDate',
            'hanyaDisetujui',
            'laporanList',
            'kop'
        ));
    }

    /**
     * Pratinjau langsung di tab browser (Stream PDF)
     */
    public function preview(Request $request): Response
    {
        $user = Auth::user();
        $now = Carbon::now();

        $bulan = (int) $request->get('bulan', $now->month);
        $tahun = (int) $request->get('tahun', $now->year);
        $mode = $request->get('mode', 'bulanan');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $hanyaDisetujui = $request->boolean('hanya_disetujui', false);

        $query = LaporanKinerja::where('user_id', $user->id);

        if ($mode === 'rentang' && $startDate && $endDate) {
            $query->whereBetween('tanggal_laporan', [$startDate, $endDate]);
            $jenis = 'harian';
        } else {
            $query->whereYear('tanggal_laporan', $tahun)
                ->whereMonth('tanggal_laporan', $bulan);
            $jenis = 'bulanan';
        }

        if ($hanyaDisetujui) {
            $query->where('status_verifikasi', 'disetujui');
        }

        $laporanList = $query->orderBy('tanggal_laporan', 'asc')
            ->orderBy('jam_mulai', 'asc')
            ->get();

        $kop = KopLaporan::getActive();

        $pdf = $this->pdfService->renderPdf(
            $user,
            $laporanList,
            $kop,
            $bulan,
            $tahun,
            $startDate,
            $endDate,
            $jenis
        );

        return $pdf->stream("Laporan_Kinerja_{$user->name}_{$tahun}_{$bulan}.pdf");
    }

    /**
     * Download file PDF sekaligus otomatis arsipkan ke storage dan database arsip_pdfs
     */
    public function download(Request $request): Response
    {
        $user = Auth::user();
        $now = Carbon::now();

        $bulan = (int) $request->get('bulan', $now->month);
        $tahun = (int) $request->get('tahun', $now->year);
        $mode = $request->get('mode', 'bulanan');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $hanyaDisetujui = $request->boolean('hanya_disetujui', false);

        $query = LaporanKinerja::where('user_id', $user->id);

        if ($mode === 'rentang' && $startDate && $endDate) {
            $query->whereBetween('tanggal_laporan', [$startDate, $endDate]);
            $jenis = 'harian';
        } else {
            $query->whereYear('tanggal_laporan', $tahun)
                ->whereMonth('tanggal_laporan', $bulan);
            $jenis = 'bulanan';
        }

        if ($hanyaDisetujui) {
            $query->where('status_verifikasi', 'disetujui');
        }

        $laporanList = $query->orderBy('tanggal_laporan', 'asc')
            ->orderBy('jam_mulai', 'asc')
            ->get();

        $kop = KopLaporan::getActive();

        // Otomatis simpan ke arsip server dan tabel arsip_pdfs
        $this->pdfService->archivePdf($user, $laporanList, $kop, $bulan, $tahun, $jenis);

        $pdf = $this->pdfService->renderPdf(
            $user,
            $laporanList,
            $kop,
            $bulan,
            $tahun,
            $startDate,
            $endDate,
            $jenis
        );

        $filename = "Laporan_Kinerja_{$user->name}_{$tahun}_{$bulan}.pdf";

        return $pdf->download($filename);
    }
}
