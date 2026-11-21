@extends('layouts.app')

@section('title', 'Tinjau & Verifikasi Laporan Kinerja')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Breadcrumb & Header -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-emerald-700">Dashboard</a>
                <span>/</span>
                <a href="{{ route('admin.verifikasi.index') }}" class="hover:text-emerald-700">Monitoring & Verifikasi</a>
                <span>/</span>
                <span class="text-slate-800 font-semibold">Tinjau Detail</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900">Evaluasi Pelaksanaan Tugas Pegawai</h1>
        </div>
        <a href="{{ route('admin.verifikasi.index') }}" class="inline-flex items-center px-3 py-1.5 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 text-slate-600 text-xs font-medium transition">
            Kembali ke Daftar
        </a>
    </div>

    <!-- Layout 2 Kolom -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Kolom Kiri: Rincian Kegiatan Pegawai & Bukti (7 Kolom) -->
        <div class="lg:col-span-7 space-y-6">
            <!-- Profil Pegawai Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-lg uppercase flex-shrink-0">
                    {{ substr($laporan->user->name, 0, 1) }}
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900">{{ $laporan->user->name }}</h3>
                    <div class="text-xs text-slate-500">NIP. {{ $laporan->user->nip }} • {{ $laporan->user->email }}</div>
                    <div class="text-[11px] font-semibold text-emerald-700 mt-0.5">{{ $laporan->user->jabatan }} • Seksi {{ $laporan->user->bidang_seksi }}</div>
                </div>
            </div>

            <!-- Detail Laporan Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 space-y-5">
                <div class="grid grid-cols-2 gap-4 pb-4 border-b border-slate-100 text-xs">
                    <div>
                        <span class="text-slate-400 font-semibold uppercase">Hari & Tanggal</span>
                        <div class="font-bold text-slate-900 mt-1 text-sm">
                            {{ \Carbon\Carbon::parse($laporan->tanggal_laporan)->translatedFormat('l, d F Y') }}
                        </div>
                    </div>
                    <div>
                        <span class="text-slate-400 font-semibold uppercase">Jam Pelaksanaan</span>
                        <div class="font-bold text-slate-900 mt-1 text-sm">
                            {{ substr($laporan->jam_mulai, 0, 5) }} s/d {{ substr($laporan->jam_selesai, 0, 5) }} WIB
                        </div>
                    </div>
                    <div>
                        <span class="text-slate-400 font-semibold uppercase">Hasil / Output</span>
                        <div class="font-bold text-slate-900 mt-1">
                            {{ $laporan->hasil_output }}
                        </div>
                    </div>
                    <div>
                        <span class="text-slate-400 font-semibold uppercase">Volume Kuantitas</span>
                        <div class="font-bold text-slate-900 mt-1">
                            {{ (float) $laporan->volume_kuantitas }} {{ $laporan->satuan }}
                        </div>
                    </div>
                </div>

                <!-- Uraian -->
                <div>
                    <span class="text-xs font-semibold text-slate-400 uppercase">Uraian Lengkap Kegiatan Tugas:</span>
                    <div class="mt-2 text-xs sm:text-sm text-slate-800 leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-200">
                        {{ $laporan->uraian_kegiatan }}
                    </div>
                </div>

                <!-- Bukti Dokumen / Foto -->
                <div>
                    <span class="text-xs font-semibold text-slate-400 uppercase">Bukti Kegiatan Terlampir:</span>
                    <div class="mt-2">
                        @if($laporan->bukti_dukung_path)
                            @php
                                $ext = strtolower(pathinfo($laporan->bukti_dukung_path, PATHINFO_EXTENSION));
                                $isImg = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                            @endphp

                            @if($isImg)
                                <div class="border border-slate-200 rounded-xl overflow-hidden">
                                    <img src="{{ asset('storage/' . $laporan->bukti_dukung_path) }}" alt="Bukti" class="w-full max-h-80 object-cover">
                                    <div class="p-3 bg-slate-50 flex items-center justify-between text-xs">
                                        <span class="text-slate-500">{{ basename($laporan->bukti_dukung_path) }}</span>
                                        <a href="{{ asset('storage/' . $laporan->bukti_dukung_path) }}" target="_blank" class="font-bold text-emerald-700 hover:text-emerald-900">
                                            Lihat Ukuran Asli ↗
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center font-bold text-xs uppercase">
                                            {{ $ext }}
                                        </div>
                                        <div>
                                            <div class="text-xs font-semibold text-slate-800">{{ basename($laporan->bukti_dukung_path) }}</div>
                                            <div class="text-[11px] text-slate-500">Dokumen Lampiran PDF</div>
                                        </div>
                                    </div>
                                    <a href="{{ asset('storage/' . $laporan->bukti_dukung_path) }}" target="_blank" class="px-3 py-1.5 bg-white border border-slate-300 rounded-lg text-xs font-medium text-slate-700 hover:bg-slate-100 transition">
                                        Unduh File
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="text-xs text-slate-400 italic bg-slate-50 p-4 rounded-xl border border-slate-100">
                                Pegawai tidak mengunggah file bukti dukung untuk kegiatan ini.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Form Penetapan Verifikasi (5 Kolom) -->
        <div class="lg:col-span-5 space-y-5">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sticky top-20">
                <h3 class="text-base font-bold text-slate-900 mb-1">Keputusan Verifikasi Pimpinan</h3>
                <p class="text-xs text-slate-500 mb-5">Berikan persetujuan atau catatan evaluasi untuk pegawai yang bersangkutan.</p>

                <form method="POST" action="{{ route('admin.verifikasi.update', $laporan) }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase mb-2">Status Persetujuan <span class="text-rose-500">*</span></label>
                        <div class="space-y-2">
                            <label class="flex items-center p-3 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer transition">
                                <input type="radio" name="status_verifikasi" value="disetujui" {{ $laporan->status_verifikasi === 'disetujui' ? 'checked' : '' }}
                                    class="h-4 w-4 text-emerald-600 focus:ring-emerald-500">
                                <div class="ml-3">
                                    <span class="block text-xs font-bold text-emerald-800">Disetujui</span>
                                    <span class="block text-[11px] text-slate-500">Kegiatan sah dan terverifikasi untuk rekap bulanan</span>
                                </div>
                            </label>

                            <label class="flex items-center p-3 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer transition">
                                <input type="radio" name="status_verifikasi" value="revisi" {{ $laporan->status_verifikasi === 'revisi' ? 'checked' : '' }}
                                    class="h-4 w-4 text-rose-600 focus:ring-rose-500">
                                <div class="ml-3">
                                    <span class="block text-xs font-bold text-rose-800">Perlu Revisi</span>
                                    <span class="block text-[11px] text-slate-500">Kembalikan ke pegawai disertai catatan koreksi</span>
                                </div>
                            </label>

                            <label class="flex items-center p-3 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer transition">
                                <input type="radio" name="status_verifikasi" value="terkirim" {{ $laporan->status_verifikasi === 'terkirim' ? 'checked' : '' }}
                                    class="h-4 w-4 text-amber-600 focus:ring-amber-500">
                                <div class="ml-3">
                                    <span class="block text-xs font-bold text-amber-800">Terkirim (Pending)</span>
                                    <span class="block text-[11px] text-slate-500">Tunda verifikasi / masih dalam peninjauan</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label for="catatan_admin" class="block text-xs font-semibold text-slate-700 uppercase mb-1">
                            Catatan Evaluasi / Arahan Pimpinan
                        </label>
                        <textarea id="catatan_admin" name="catatan_admin" rows="4" placeholder="Tulis catatan evaluasi atau alasan revisi jika ada..."
                            class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">{{ old('catatan_admin', $laporan->catatan_admin) }}</textarea>
                        <p class="text-[11px] text-slate-400 mt-1">Catatan ini akan langsung terlihat oleh pegawai di dashboard mereka.</p>
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-md transition">
                        Simpan Keputusan Verifikasi
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
