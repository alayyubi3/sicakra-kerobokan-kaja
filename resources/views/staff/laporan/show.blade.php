@extends('layouts.app')

@section('title', 'Detail Laporan Kinerja')

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
                <span class="text-slate-800 font-semibold">Detail Laporan</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900">Rincian Laporan Kinerja Harian</h1>
        </div>
        <div class="flex items-center space-x-2">
            @if($laporan->canBeEdited())
                <a href="{{ route('staff.laporan.edit', $laporan) }}" class="inline-flex items-center px-3.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-sm transition">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Sunting Laporan
                </a>
            @endif
            <a href="{{ route('staff.laporan.index') }}" class="inline-flex items-center px-3 py-1.5 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 text-slate-600 text-xs font-medium transition">
                Kembali
            </a>
        </div>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <!-- Status Header Banner -->
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 {{ $laporan->status_verifikasi === 'disetujui' ? 'bg-emerald-50/70' : ($laporan->status_verifikasi === 'revisi' ? 'bg-rose-50/70' : 'bg-amber-50/70') }}">
            <div>
                <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Status Verifikasi</span>
                <div class="mt-1 flex items-center space-x-2">
                    @if($laporan->status_verifikasi === 'disetujui')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-600 text-white shadow-sm">
                            <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                            Disetujui Pimpinan
                        </span>
                    @elseif($laporan->status_verifikasi === 'revisi')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-rose-600 text-white shadow-sm">
                            <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                            Perlu Revisi
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-500 text-white shadow-sm">
                            <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
                            Terkirim (Menunggu Evaluasi)
                        </span>
                    @endif
                </div>
            </div>

            <div class="text-xs text-slate-500 text-left sm:text-right">
                <div>Diinput pada: {{ $laporan->created_at->translatedFormat('d F Y, H:i') }} WIB</div>
                @if($laporan->updated_at != $laporan->created_at)
                    <div class="text-[11px] text-slate-400 mt-0.5">Terakhir diperbarui: {{ $laporan->updated_at->diffForHumans() }}</div>
                @endif
            </div>
        </div>

        <!-- Catatan Evaluasi Admin -->
        @if($laporan->catatan_admin)
            <div class="p-6 border-b border-slate-100 bg-slate-50">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Catatan Evaluasi Pimpinan:</h4>
                <p class="text-sm font-semibold text-slate-800 bg-white p-3.5 rounded-xl border border-slate-200">
                    "{{ $laporan->catatan_admin }}"
                </p>
            </div>
        @endif

        <!-- Details Grid -->
        <div class="p-6 sm:p-8 space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <span class="text-xs font-semibold uppercase text-slate-400">Hari & Tanggal</span>
                    <div class="text-sm font-bold text-slate-900 mt-1">
                        {{ \Carbon\Carbon::parse($laporan->tanggal_laporan)->translatedFormat('l, d F Y') }}
                    </div>
                </div>

                <div>
                    <span class="text-xs font-semibold uppercase text-slate-400">Waktu Pelaksanaan</span>
                    <div class="text-sm font-bold text-slate-900 mt-1">
                        {{ substr($laporan->jam_mulai, 0, 5) }} s/d {{ substr($laporan->jam_selesai, 0, 5) }} WIB
                    </div>
                </div>

                <div>
                    <span class="text-xs font-semibold uppercase text-slate-400">Hasil / Output Capaian</span>
                    <div class="text-sm font-bold text-slate-900 mt-1">
                        {{ $laporan->hasil_output }}
                    </div>
                </div>

                <div>
                    <span class="text-xs font-semibold uppercase text-slate-400">Volume / Kuantitas</span>
                    <div class="text-sm font-bold text-slate-900 mt-1">
                        {{ (float) $laporan->volume_kuantitas }} {{ $laporan->satuan }}
                    </div>
                </div>
            </div>

            <!-- Uraian Lengkap -->
            <div>
                <span class="text-xs font-semibold uppercase text-slate-400">Uraian Kegiatan Tugas</span>
                <div class="mt-2 text-sm text-slate-800 leading-relaxed bg-slate-50 p-4 rounded-xl border border-slate-100">
                    {{ $laporan->uraian_kegiatan }}
                </div>
            </div>

            <!-- Bukti Dokumen -->
            <div>
                <span class="text-xs font-semibold uppercase text-slate-400">Bukti Kegiatan</span>
                <div class="mt-2">
                    @if($laporan->bukti_dukung_path)
                        @php
                            $extension = strtolower(pathinfo($laporan->bukti_dukung_path, PATHINFO_EXTENSION));
                            $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                        @endphp

                        @if($isImage)
                            <div class="border border-slate-200 rounded-xl overflow-hidden max-w-md">
                                <img src="{{ asset('storage/' . $laporan->bukti_dukung_path) }}" alt="Foto Bukti Kegiatan" class="w-full max-h-80 object-cover">
                                <div class="p-3 bg-slate-50 flex items-center justify-between text-xs">
                                    <span class="text-slate-500">{{ basename($laporan->bukti_dukung_path) }}</span>
                                    <a href="{{ asset('storage/' . $laporan->bukti_dukung_path) }}" target="_blank" class="font-semibold text-emerald-700 hover:text-emerald-900">
                                        Buka Gambar Penuh ↗
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between max-w-md">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center font-bold text-xs uppercase">
                                        {{ $extension }}
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
                            Tidak ada file bukti dukung yang dilampirkan pada kegiatan ini.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
