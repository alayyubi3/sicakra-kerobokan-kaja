@extends('layouts.app')

@section('title', 'Input Laporan Kinerja Harian')

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
                <span class="text-slate-800 font-semibold">Input Baru</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900">Form Catatan Kinerja Harian Pegawai</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Isi rincian tugas dan aktivitas kerja harian kelurahan Anda dengan lengkap.</p>
        </div>
        <a href="{{ route('staff.laporan.index') }}" class="inline-flex items-center px-3 py-1.5 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 text-slate-600 text-xs font-medium transition">
            Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
        <form method="POST" action="{{ route('staff.laporan.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Tanggal & Jam Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Tanggal Laporan -->
                <div>
                    <label for="tanggal_laporan" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Tanggal Kegiatan <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" id="tanggal_laporan" name="tanggal_laporan" required
                        value="{{ old('tanggal_laporan', date('Y-m-d')) }}"
                        class="w-full px-3 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <!-- Jam Mulai -->
                <div>
                    <label for="jam_mulai" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Jam Mulai <span class="text-rose-500">*</span>
                    </label>
                    <input type="time" id="jam_mulai" name="jam_mulai" required
                        value="{{ old('jam_mulai', '08:00') }}"
                        class="w-full px-3 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <!-- Jam Selesai -->
                <div>
                    <label for="jam_selesai" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Jam Selesai <span class="text-rose-500">*</span>
                    </label>
                    <input type="time" id="jam_selesai" name="jam_selesai" required
                        value="{{ old('jam_selesai', '11:00') }}"
                        class="w-full px-3 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>

            <!-- Uraian Kegiatan -->
            <div>
                <label for="uraian_kegiatan" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Uraian Kegiatan Tugas <span class="text-rose-500">*</span>
                </label>
                <textarea id="uraian_kegiatan" name="uraian_kegiatan" rows="4" required
                    placeholder="Jelaskan secara spesifik aktivitas kerja yang telah dilaksanakan..."
                    class="w-full px-3 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">{{ old('uraian_kegiatan') }}</textarea>
                <p class="text-[11px] text-slate-400 mt-1">Uraikan tugas dengan bahasa formal, jelas, dan dapat diverifikasi.</p>
            </div>

            <!-- Output Hasil & Volume Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Hasil / Output -->
                <div class="sm:col-span-1">
                    <label for="hasil_output" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Hasil / Output <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="hasil_output" name="hasil_output" required
                        value="{{ old('hasil_output') }}"
                        placeholder="Contoh: Dokumen verifikasi SKCK"
                        class="w-full px-3 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <!-- Volume Kuantitas -->
                <div>
                    <label for="volume_kuantitas" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Volume Kuantitas <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" step="0.1" min="0.1" id="volume_kuantitas" name="volume_kuantitas" required
                        value="{{ old('volume_kuantitas', 1) }}"
                        class="w-full px-3 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <!-- Satuan -->
                <div>
                    <label for="satuan" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Satuan <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="satuan" name="satuan" required
                        list="daftar-satuan"
                        value="{{ old('satuan', 'Berkas') }}"
                        placeholder="Contoh: Berkas, Kegiatan, Dokumen"
                        class="w-full px-3 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <datalist id="daftar-satuan">
                        <option value="Berkas">
                        <option value="Kegiatan">
                        <option value="Dokumen">
                        <option value="Layanan">
                        <option value="Warga">
                        <option value="Laporan">
                        <option value="Surat">
                    </datalist>
                </div>
            </div>

            <!-- Upload Foto / Dokumen Bukti Dukung -->
            <div class="border-t border-slate-100 pt-5">
                <label for="bukti_dukung" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Bukti Dukung Kegiatan (Opsional)
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-xl hover:border-emerald-500 transition">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-10 w-10 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-slate-600 justify-center">
                            <label for="bukti_dukung" class="relative cursor-pointer rounded-md font-medium text-emerald-600 hover:text-emerald-500 focus-within:outline-none">
                                <span>Pilih file dokumen / foto</span>
                                <input id="bukti_dukung" name="bukti_dukung" type="file" accept=".jpg,.jpeg,.png,.pdf" class="sr-only">
                            </label>
                        </div>
                        <p class="text-xs text-slate-500">Mendukung format JPG, PNG, atau PDF (maksimal 5 MB)</p>
                    </div>
                </div>
            </div>

            <!-- Submit Button & Action -->
            <div class="border-t border-slate-200 pt-5 flex items-center justify-end space-x-3">
                <a href="{{ route('staff.laporan.index') }}" class="px-4 py-2.5 border border-slate-300 rounded-xl text-slate-700 text-xs font-medium hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold shadow-md transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan & Ajukan Laporan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
