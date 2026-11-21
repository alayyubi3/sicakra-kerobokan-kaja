<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\LaporanKinerja;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LaporanKinerjaController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();

        $query = LaporanKinerja::where('user_id', $user->id);

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
                    ->orWhere('hasil_output', 'like', "%{$keyword}%");
            });
        }

        $laporanList = $query->orderBy('tanggal_laporan', 'desc')
            ->orderBy('jam_mulai', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('staff.laporan.index', compact('laporanList'));
    }

    public function create(): View
    {
        return view('staff.laporan.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tanggal_laporan' => ['required', 'date'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'uraian_kegiatan' => ['required', 'string', 'min:5'],
            'hasil_output' => ['required', 'string', 'max:255'],
            'volume_kuantitas' => ['required', 'numeric', 'min:0.1'],
            'satuan' => ['required', 'string', 'max:50'],
            'bukti_dukung' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'], // 5MB
        ], [
            'tanggal_laporan.required' => 'Tanggal laporan wajib diisi.',
            'jam_mulai.required' => 'Jam mulai kegiatan wajib diisi.',
            'jam_selesai.required' => 'Jam selesai kegiatan wajib diisi.',
            'jam_selesai.after' => 'Jam selesai harus lebih akhir dari jam mulai.',
            'uraian_kegiatan.required' => 'Uraian kegiatan wajib dijelaskan secara rinci.',
            'hasil_output.required' => 'Hasil / Output kegiatan wajib diisi.',
            'volume_kuantitas.required' => 'Volume kuantitas wajib diisi.',
            'satuan.required' => 'Satuan output wajib diisi.',
            'bukti_dukung.mimes' => 'Format file bukti harus berupa JPG, PNG, atau PDF.',
            'bukti_dukung.max' => 'Ukuran file bukti maksimal 5 MB.',
        ]);

        $buktiPath = null;
        if ($request->hasFile('bukti_dukung')) {
            $buktiPath = $request->file('bukti_dukung')->store('bukti_kegiatan', 'public');
        }

        LaporanKinerja::create([
            'user_id' => Auth::id(),
            'tanggal_laporan' => $validated['tanggal_laporan'],
            'jam_mulai' => $validated['jam_mulai'],
            'jam_selesai' => $validated['jam_selesai'],
            'uraian_kegiatan' => $validated['uraian_kegiatan'],
            'hasil_output' => $validated['hasil_output'],
            'volume_kuantitas' => $validated['volume_kuantitas'],
            'satuan' => $validated['satuan'],
            'bukti_dukung_path' => $buktiPath,
            'status_verifikasi' => 'terkirim',
            'catatan_admin' => null,
        ]);

        return redirect()->route('staff.laporan.index')
            ->with('success', 'Laporan kinerja harian berhasil disimpan dan dikirim untuk verifikasi pimpinan.');
    }

    public function show(LaporanKinerja $laporan): View|RedirectResponse
    {
        if ($laporan->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat laporan ini.');
        }

        return view('staff.laporan.show', compact('laporan'));
    }

    public function edit(LaporanKinerja $laporan): View|RedirectResponse
    {
        if ($laporan->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengedit laporan ini.');
        }

        if (! $laporan->canBeEdited()) {
            return redirect()->route('staff.laporan.index')
                ->with('error', 'Laporan ini telah disetujui oleh Administrator dan tidak dapat diubah lagi.');
        }

        return view('staff.laporan.edit', compact('laporan'));
    }

    public function update(Request $request, LaporanKinerja $laporan): RedirectResponse
    {
        if ($laporan->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengubah laporan ini.');
        }

        if (! $laporan->canBeEdited()) {
            return redirect()->route('staff.laporan.index')
                ->with('error', 'Laporan yang telah disetujui tidak dapat disunting kembali.');
        }

        $validated = $request->validate([
            'tanggal_laporan' => ['required', 'date'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'uraian_kegiatan' => ['required', 'string', 'min:5'],
            'hasil_output' => ['required', 'string', 'max:255'],
            'volume_kuantitas' => ['required', 'numeric', 'min:0.1'],
            'satuan' => ['required', 'string', 'max:50'],
            'bukti_dukung' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ], [
            'jam_selesai.after' => 'Jam selesai harus lebih akhir dari jam mulai.',
            'bukti_dukung.mimes' => 'Format file bukti harus berupa JPG, PNG, atau PDF.',
            'bukti_dukung.max' => 'Ukuran file bukti maksimal 5 MB.',
        ]);

        $buktiPath = $laporan->bukti_dukung_path;
        if ($request->hasFile('bukti_dukung')) {
            if ($buktiPath && Storage::disk('public')->exists($buktiPath)) {
                Storage::disk('public')->delete($buktiPath);
            }
            $buktiPath = $request->file('bukti_dukung')->store('bukti_kegiatan', 'public');
        }

        $laporan->update([
            'tanggal_laporan' => $validated['tanggal_laporan'],
            'jam_mulai' => $validated['jam_mulai'],
            'jam_selesai' => $validated['jam_selesai'],
            'uraian_kegiatan' => $validated['uraian_kegiatan'],
            'hasil_output' => $validated['hasil_output'],
            'volume_kuantitas' => $validated['volume_kuantitas'],
            'satuan' => $validated['satuan'],
            'bukti_dukung_path' => $buktiPath,
            'status_verifikasi' => 'terkirim', // jika sebelumnya revisi, reset ke terkirim untuk verifikasi ulang
        ]);

        return redirect()->route('staff.laporan.index')
            ->with('success', 'Perubahan laporan kinerja berhasil diperbarui dan diajukan ulang.');
    }

    public function destroy(LaporanKinerja $laporan): RedirectResponse
    {
        if ($laporan->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk menghapus laporan ini.');
        }

        if (! $laporan->canBeEdited()) {
            return redirect()->route('staff.laporan.index')
                ->with('error', 'Laporan yang telah disetujui tidak dapat dihapus.');
        }

        if ($laporan->bukti_dukung_path && Storage::disk('public')->exists($laporan->bukti_dukung_path)) {
            Storage::disk('public')->delete($laporan->bukti_dukung_path);
        }

        $laporan->delete();

        return redirect()->route('staff.laporan.index')
            ->with('success', 'Laporan kinerja berhasil dihapus.');
    }
}
