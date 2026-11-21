@extends('layouts.app')

@section('title', 'Sunting Laporan Kinerja')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Breadcrumb & Header -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('staff.dashboard') }}" class="hover:text-emerald-700">Dashboard</a>
                <span>/</span>
                <a href="{{ route('staff.laporan.index') }}" class="hover:text-emerald-700">Laporan Kinerja</a>
                <span>/</span>
                <span class="text-slate-800 font-semibold">Sunting Laporan</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900">Perbarui Catatan Kinerja Harian</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Lakukan penyesuaian atau perbaikan pada kegiatan yang belum disahkan.</p>
        </div>
        <a href="{{ route('staff.laporan.index') }}" class="inline-flex items-center px-3 py-1.5 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 text-slate-600 text-xs font-medium transition">
            Kembali
        </a>
    </div>

    <!-- Catatan Revisi Pimpinan (Jika ada) -->
    @if($laporan->status_verifikasi === 'revisi' && $laporan->catatan_admin)
        <div class="rounded-xl bg-rose-50 border border-rose-200 p-4 shadow-sm">
            <div class="flex items-start space-x-3">
                <svg class="w-5 h-5 text-rose-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-rose-900">Catatan Evaluasi / Permintaan Revisi dari Pimpinan:</h4>
                    <p class="text-sm font-medium text-rose-800 mt-1 italic">"{{ $laporan->catatan_admin }}"</p>
                    <p class="text-xs text-rose-600 mt-1">Setelah disimpan, status laporan akan otomatis diajukan ulang untuk verifikasi pimpinan.</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Form Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
        <form method="POST" action="{{ route('staff.laporan.update', $laporan) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Tanggal & Jam Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Tanggal Laporan -->
                <div>
                    <label for="tanggal_laporan" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Tanggal Kegiatan <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" id="tanggal_laporan" name="tanggal_laporan" required
                        value="{{ old('tanggal_laporan', $laporan->tanggal_laporan->format('Y-m-d')) }}"
                        class="w-full px-3 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <!-- Jam Mulai -->
                <div>
                    <label for="jam_mulai" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Jam Mulai <span class="text-rose-500">*</span>
                    </label>
                    <input type="time" id="jam_mulai" name="jam_mulai" required
                        value="{{ old('jam_mulai', substr($laporan->jam_mulai, 0, 5)) }}"
                        class="w-full px-3 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <!-- Jam Selesai -->
                <div>
                    <label for="jam_selesai" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Jam Selesai <span class="text-rose-500">*</span>
                    </label>
                    <input type="time" id="jam_selesai" name="jam_selesai" required
                        value="{{ old('jam_selesai', substr($laporan->jam_selesai, 0, 5)) }}"
                        class="w-full px-3 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>

            <!-- Uraian Kegiatan -->
            <div>
                <label for="uraian_kegiatan" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Uraian Kegiatan Tugas <span class="text-rose-500">*</span>
                </label>
                <textarea id="uraian_kegiatan" name="uraian_kegiatan" rows="4" required
                    class="w-full px-3 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">{{ old('uraian_kegiatan', $laporan->uraian_kegiatan) }}</textarea>
            </div>

            <!-- Output Hasil & Volume Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Hasil / Output -->
                <div class="sm:col-span-1">
                    <label for="hasil_output" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Hasil / Output <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="hasil_output" name="hasil_output" required
                        value="{{ old('hasil_output', $laporan->hasil_output) }}"
                        class="w-full px-3 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <!-- Volume Kuantitas -->
                <div>
                    <label for="volume_kuantitas" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Volume Kuantitas <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" step="0.1" min="0.1" id="volume_kuantitas" name="volume_kuantitas" required
                        value="{{ old('volume_kuantitas', (float) $laporan->volume_kuantitas) }}"
                        class="w-full px-3 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <!-- Satuan -->
                <div>
                    <label for="satuan" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Satuan <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="satuan" name="satuan" required
                        value="{{ old('satuan', $laporan->satuan) }}"
                        class="w-full px-3 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>

            <!-- Upload Foto / Dokumen Bukti Dukung -->
            <div class="border-t border-slate-100 pt-5">
                <label for="bukti_dukung" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Ganti / Unggah Bukti Dukung Baru
                </label>

                @if($laporan->bukti_dukung_path)
                    <div class="mb-3 p-3 bg-slate-50 rounded-lg border border-slate-200 flex items-center justify-between text-xs">
                        <div class="flex items-center space-x-2 text-slate-700">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>File bukti saat ini: <strong>{{ basename($laporan->bukti_dukung_path) }}</strong></span>
                        </div>
                        <a href="{{ asset('storage/' . $laporan->bukti_dukung_path) }}" target="_blank" class="text-emerald-700 hover:text-emerald-900 font-semibold underline">
                            Lihat File
                        </a>
                    </div>
                @endif

                <input id="bukti_dukung" name="bukti_dukung" type="file" accept=".jpg,.jpeg,.png,.pdf"
                    class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition">
                <p class="text-[11px] text-slate-400 mt-1">Kosongkan jika tidak ingin mengubah file bukti yang sudah ada.</p>
            </div>

            <!-- Submit Button & Action -->
            <div class="border-t border-slate-200 pt-5 flex items-center justify-end space-x-3">
                <a href="{{ route('staff.laporan.index') }}" class="px-4 py-2.5 border border-slate-300 rounded-xl text-slate-700 text-xs font-medium hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold shadow-md transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Perubahan Laporan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
