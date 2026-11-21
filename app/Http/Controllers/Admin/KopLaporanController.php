<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KopLaporan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class KopLaporanController extends Controller
{
    public function edit(): View
    {
        $kop = KopLaporan::getActive() ?: new KopLaporan([
            'nama_pemerintah' => 'PEMERINTAH KABUPATEN BADUNG',
            'nama_kecamatan' => 'KECAMATAN KUTA UTARA',
            'nama_kelurahan' => 'KELURAHAN KEROBOKAN KAJA',
            'alamat_kantor' => 'Jl. Raya Kerobokan No. 100, Kerobokan Kaja, Kec. Kuta Utara, Kabupaten Badung, Bali 80361',
            'kontak_kantor' => 'Telp. (0361) 425123 | Email: kelurahankerobokankaja@badungkab.go.id',
            'nama_lurah' => 'I Gusti Ngurah Agung, S.STP., M.Si.',
            'nip_lurah' => '19850412 201001 1 012',
            'jabatan_penandatangan' => 'Lurah Kerobokan Kaja',
            'teks_pembuka_laporan' => 'Rekapitulasi pelaksanaan tugas pokok, fungsi, dan kegiatan harian aparatur pegawai di lingkungan Kantor Kelurahan Kerobokan Kaja:',
            'teks_penutup_laporan' => 'Demikian laporan kinerja harian/bulanan ini dibuat dengan sebenar-benarnya untuk dipergunakan sebagai bahan evaluasi dan arsip kepegawaian.',
            'is_active' => true,
        ]);

        return view('admin.kop.edit', compact('kop'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_pemerintah' => ['required', 'string', 'max:255'],
            'nama_kecamatan' => ['required', 'string', 'max:255'],
            'nama_kelurahan' => ['required', 'string', 'max:255'],
            'alamat_kantor' => ['required', 'string', 'max:255'],
            'kontak_kantor' => ['nullable', 'string', 'max:255'],
            'nama_lurah' => ['required', 'string', 'max:255'],
            'nip_lurah' => ['required', 'string', 'max:50'],
            'jabatan_penandatangan' => ['required', 'string', 'max:255'],
            'teks_pembuka_laporan' => ['nullable', 'string'],
            'teks_penutup_laporan' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg', 'max:2048'], // 2MB
        ], [
            'nama_pemerintah.required' => 'Nama instansi pemerintah wajib diisi.',
            'nama_kecamatan.required' => 'Nama kecamatan wajib diisi.',
            'nama_kelurahan.required' => 'Nama kelurahan wajib diisi.',
            'alamat_kantor.required' => 'Alamat kantor wajib diisi.',
            'nama_lurah.required' => 'Nama Lurah / Pimpinan wajib diisi.',
            'nip_lurah.required' => 'NIP Lurah / Pimpinan wajib diisi.',
            'logo.max' => 'Ukuran logo maksimal 2 MB.',
        ]);

        $kop = KopLaporan::getActive();

        if (! $kop) {
            $kop = new KopLaporan;
        }

        if ($request->hasFile('logo')) {
            if ($kop->logo_path && Storage::disk('public')->exists($kop->logo_path)) {
                Storage::disk('public')->delete($kop->logo_path);
            }
            $validated['logo_path'] = $request->file('logo')->store('kop_logos', 'public');
        }

        $kop->fill([
            'nama_pemerintah' => $validated['nama_pemerintah'],
            'nama_kecamatan' => $validated['nama_kecamatan'],
            'nama_kelurahan' => $validated['nama_kelurahan'],
            'alamat_kantor' => $validated['alamat_kantor'],
            'kontak_kantor' => $validated['kontak_kantor'],
            'logo_path' => $validated['logo_path'] ?? $kop->logo_path,
            'nama_lurah' => $validated['nama_lurah'],
            'nip_lurah' => $validated['nip_lurah'],
            'jabatan_penandatangan' => $validated['jabatan_penandatangan'],
            'teks_pembuka_laporan' => $validated['teks_pembuka_laporan'],
            'teks_penutup_laporan' => $validated['teks_penutup_laporan'],
            'is_active' => true,
        ]);

        $kop->save();

        return redirect()->route('admin.kop.edit')
            ->with('success', 'Pengaturan template kop dan tanda tangan laporan berhasil diperbarui.');
    }
}
