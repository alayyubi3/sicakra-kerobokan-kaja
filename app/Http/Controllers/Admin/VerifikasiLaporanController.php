<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanKinerja;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VerifikasiLaporanController extends Controller
{
    public function index(Request $request): View
    {
        $query = LaporanKinerja::with('user');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('bidang_seksi')) {
            $bidang = $request->bidang_seksi;
            $query->whereHas('user', function ($q) use ($bidang) {
                $q->where('bidang_seksi', $bidang);
            });
        }

        if ($request->filled('status')) {
            $query->where('status_verifikasi', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_laporan', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_laporan', '<=', $request->end_date);
        }

        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($q) use ($keyword) {
                $q->where('uraian_kegiatan', 'like', "%{$keyword}%")
                    ->orWhere('hasil_output', 'like', "%{$keyword}%")
                    ->orWhereHas('user', function ($qu) use ($keyword) {
                        $qu->where('name', 'like', "%{$keyword}%")
                            ->orWhere('nip', 'like', "%{$keyword}%");
                    });
            });
        }

        $laporanList = $query->orderBy('tanggal_laporan', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        $daftarPegawai = User::where('role', 'staff')
            ->orderBy('name', 'asc')
            ->get();

        $daftarBidang = User::whereNotNull('bidang_seksi')
            ->distinct()
            ->pluck('bidang_seksi')
            ->filter();

        $countTerkirim = LaporanKinerja::where('status_verifikasi', 'terkirim')->count();
        $countDisetujui = LaporanKinerja::where('status_verifikasi', 'disetujui')->count();
        $countRevisi = LaporanKinerja::where('status_verifikasi', 'revisi')->count();

        return view('admin.verifikasi.index', compact(
            'laporanList',
            'daftarPegawai',
            'daftarBidang',
            'countTerkirim',
            'countDisetujui',
            'countRevisi'
        ));
    }

    public function show(LaporanKinerja $laporan): View
    {
        $laporan->load('user');

        return view('admin.verifikasi.show', compact('laporan'));
    }

    public function updateVerifikasi(Request $request, LaporanKinerja $laporan): RedirectResponse
    {
        $validated = $request->validate([
            'status_verifikasi' => ['required', 'in:disetujui,revisi,terkirim'],
            'catatan_admin' => [
                'nullable',
                'string',
                'required_if:status_verifikasi,revisi',
            ],
        ], [
            'catatan_admin.required_if' => 'Catatan evaluasi wajib diisi jika status laporan adalah Revisi.',
        ]);

        $laporan->update([
            'status_verifikasi' => $validated['status_verifikasi'],
            'catatan_admin' => $validated['catatan_admin'] ?? null,
        ]);

        $statusLabel = [
            'disetujui' => 'Disetujui',
            'revisi' => 'Perlu Revisi',
            'terkirim' => 'Terkirim',
        ][$validated['status_verifikasi']] ?? $validated['status_verifikasi'];

        return back()->with('success', "Status laporan berhasil diperbarui menjadi {$statusLabel}.");
    }

    public function bulkApprove(Request $request): RedirectResponse
    {
        $ids = $request->input('selected_ids', []);

        if (empty($ids) || ! is_array($ids)) {
            return back()->with('error', 'Tidak ada laporan yang dipilih untuk disetujui.');
        }

        $count = LaporanKinerja::whereIn('id', $ids)
            ->where('status_verifikasi', '!=', 'disetujui')
            ->update([
                'status_verifikasi' => 'disetujui',
            ]);

        return back()->with('success', "Berhasil menyetujui {$count} laporan kinerja secara massal.");
    }
}
