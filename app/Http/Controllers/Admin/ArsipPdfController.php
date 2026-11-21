<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArsipPdf;
use App\Models\KopLaporan;
use App\Models\LaporanKinerja;
use App\Models\User;
use App\Services\LaporanPdfService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ArsipPdfController extends Controller
{
    public function __construct(
        protected LaporanPdfService $pdfService
    ) {}

    public function index(Request $request): View
    {
        $query = ArsipPdf::with('user');

        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($q) use ($keyword) {
                $q->where('nomor_arsip', 'like', "%{$keyword}%")
                    ->orWhereHas('user', function ($qu) use ($keyword) {
                        $qu->where('name', 'like', "%{$keyword}%")
                            ->orWhere('nip', 'like', "%{$keyword}%");
                    });
            });
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('bulan')) {
            $query->where('periode_bulan', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->where('periode_tahun', $request->tahun);
        }

        $arsipList = $query->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        $daftarPegawai = User::where('role', 'staff')
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.arsip.index', compact('arsipList', 'daftarPegawai'));
    }

    /**
     * Tampilkan pratinjau PDF di browser tab
     */
    public function preview(ArsipPdf $arsip): BinaryFileResponse|RedirectResponse
    {
        if (! Storage::disk('public')->exists($arsip->file_path)) {
            return back()->with('error', 'File fisik arsip PDF tidak ditemukan di server.');
        }

        $fullPath = Storage::disk('public')->path($arsip->file_path);

        return response()->file($fullPath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.basename($arsip->file_path).'"',
        ]);
    }

    /**
     * Unduh file PDF arsip
     */
    public function download(ArsipPdf $arsip): BinaryFileResponse|RedirectResponse
    {
        if (! Storage::disk('public')->exists($arsip->file_path)) {
            return back()->with('error', 'File fisik arsip PDF tidak ditemukan di server.');
        }

        $fullPath = Storage::disk('public')->path($arsip->file_path);
        $downloadName = "ARSIP_{$arsip->user->nip}_{$arsip->periode_tahun}_{$arsip->periode_bulan}.pdf";

        return response()->download($fullPath, $downloadName);
    }

    /**
     * Admin generate arsip baru secara manual untuk pegawai tertentu
     */
    public function generateManual(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'bulan' => ['required', 'integer', 'between:1,12'],
            'tahun' => ['required', 'integer', 'between:2020,2035'],
            'hanya_disetujui' => ['boolean'],
        ]);

        $user = User::findOrFail($validated['user_id']);
        $bulan = (int) $validated['bulan'];
        $tahun = (int) $validated['tahun'];
        $hanyaDisetujui = $request->boolean('hanya_disetujui', true);

        $query = LaporanKinerja::where('user_id', $user->id)
            ->whereYear('tanggal_laporan', $tahun)
            ->whereMonth('tanggal_laporan', $bulan);

        if ($hanyaDisetujui) {
            $query->where('status_verifikasi', 'disetujui');
        }

        $laporanList = $query->orderBy('tanggal_laporan', 'asc')
            ->orderBy('jam_mulai', 'asc')
            ->get();

        if ($laporanList->isEmpty()) {
            return back()->with('error', "Tidak ditemukan data laporan kinerja untuk pegawai {$user->name} pada periode tersebut.");
        }

        $kop = KopLaporan::getActive();

        $arsip = $this->pdfService->archivePdf($user, $laporanList, $kop, $bulan, $tahun, 'bulanan');

        return redirect()->route('admin.arsip.index')
            ->with('success', "Dokumen PDF berhasil digenerate dan diarsipkan dengan nomor: {$arsip->nomor_arsip}");
    }

    /**
     * Hapus arsip
     */
    public function destroy(ArsipPdf $arsip): RedirectResponse
    {
        if (Storage::disk('public')->exists($arsip->file_path)) {
            Storage::disk('public')->delete($arsip->file_path);
        }

        $nomor = $arsip->nomor_arsip;
        $arsip->delete();

        return redirect()->route('admin.arsip.index')
            ->with('success', "Dokumen arsip {$nomor} berhasil dihapus.");
    }
}
