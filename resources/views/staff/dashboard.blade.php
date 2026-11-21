@extends('layouts.app')

@section('title', 'Dashboard Pegawai')

@section('content')
<div class="space-y-6">

    <!-- Header & Welcome Banner -->
    <div class="bg-gradient-to-r from-emerald-800 via-emerald-700 to-teal-800 rounded-2xl p-6 sm:p-8 text-white shadow-lg relative overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="inline-flex items-center space-x-2 bg-emerald-900/60 backdrop-blur-sm px-3 py-1 rounded-full text-xs text-emerald-200 mb-3 border border-emerald-500/30">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">Selamat Datang, {{ $user->name }}</h1>
                <p class="text-emerald-100 text-sm mt-1 max-w-2xl">
                    {{ $user->jabatan }} • Bidang {{ $user->bidang_seksi }}
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('staff.laporan.create') }}" class="inline-flex items-center px-4 py-2.5 bg-white text-emerald-800 font-semibold text-sm rounded-xl shadow-md hover:bg-emerald-50 transition transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 mr-1.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Input Kegiatan Hari Ini
                </a>
                <a href="{{ route('staff.cetak.index') }}" class="inline-flex items-center px-4 py-2.5 bg-emerald-900/60 hover:bg-emerald-900 text-white font-medium text-sm rounded-xl border border-emerald-400/30 transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Cetak Laporan
                </a>
            </div>
        </div>

        <!-- Subtle Background Decorative Shape -->
        <div class="absolute -right-8 -bottom-10 w-64 h-64 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
    </div>

    <!-- Alert Revisi (Jika Ada) -->
    @if($laporanPerluRevisi->isNotEmpty())
        <div class="rounded-xl bg-rose-50 border border-rose-200 p-5 shadow-sm">
            <div class="flex items-start space-x-3">
                <div class="p-2 bg-rose-100 rounded-lg text-rose-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-bold text-rose-900">Perhatian: Ada {{ $laporanPerluRevisi->count() }} Laporan Kinerja Perlu Direvisi</h3>
                    <p class="text-xs text-rose-700 mt-0.5">Pimpinan telah memberikan catatan evaluasi. Silakan periksa dan perbarui laporan Anda agar dapat disetujui.</p>

                    <div class="mt-3 space-y-2">
                        @foreach($laporanPerluRevisi as $revisi)
                            <div class="bg-white p-3 rounded-lg border border-rose-200 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                <div class="text-xs">
                                    <div class="font-semibold text-slate-900">Tanggal {{ \Carbon\Carbon::parse($revisi->tanggal_laporan)->format('d/m/Y') }} — {{ $revisi->hasil_output }}</div>
                                    <div class="text-rose-600 mt-0.5 italic">Catatan Admin: "{{ $revisi->catatan_admin }}"</div>
                                </div>
                                <a href="{{ route('staff.laporan.edit', $revisi) }}" class="inline-flex items-center px-3 py-1 bg-rose-600 hover:bg-rose-700 text-white rounded-md text-xs font-semibold self-start sm:self-auto transition">
                                    Perbaiki Sekarang
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Statistic Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Laporan Bulan Ini -->
        <div class="bg-white rounded-xl p-5 border border-slate-200/80 shadow-sm hover:shadow transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Bulan Ini</span>
                <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>
            <div class="mt-3 flex items-baseline space-x-2">
                <span class="text-3xl font-extrabold text-slate-900">{{ $totalLaporanBulanIni }}</span>
                <span class="text-xs text-slate-500">laporan kegiatan</span>
            </div>
            <div class="mt-2 text-xs text-slate-400">
                Total keseluruhan riwayat: <strong class="text-slate-600">{{ $totalSemuaLaporan }}</strong>
            </div>
        </div>

        <!-- Disetujui -->
        <div class="bg-white rounded-xl p-5 border border-slate-200/80 shadow-sm hover:shadow transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-emerald-700">Disetujui</span>
                <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
            </div>
            <div class="mt-3 flex items-baseline space-x-2">
                <span class="text-3xl font-extrabold text-emerald-600">{{ $totalDisetujui }}</span>
                <span class="text-xs text-slate-500">kegiatan sah</span>
            </div>
            <div class="mt-2 text-xs text-emerald-600 font-medium">
                {{ $totalLaporanBulanIni > 0 ? round(($totalDisetujui / $totalLaporanBulanIni) * 100) : 0 }}% dari total input bulan ini
            </div>
        </div>

        <!-- Terkirim / Menunggu -->
        <div class="bg-white rounded-xl p-5 border border-slate-200/80 shadow-sm hover:shadow transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-amber-700">Menunggu Verifikasi</span>
                <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-3 flex items-baseline space-x-2">
                <span class="text-3xl font-extrabold text-amber-600">{{ $totalTerkirim }}</span>
                <span class="text-xs text-slate-500">dalam antrean</span>
            </div>
            <div class="mt-2 text-xs text-slate-400">
                Menunggu peninjauan pimpinan
            </div>
        </div>

        <!-- Perlu Revisi -->
        <div class="bg-white rounded-xl p-5 border border-slate-200/80 shadow-sm hover:shadow transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold uppercase tracking-wider text-rose-700">Perlu Revisi</span>
                <div class="w-10 h-10 rounded-lg bg-rose-50 flex items-center justify-center text-rose-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
            </div>
            <div class="mt-3 flex items-baseline space-x-2">
                <span class="text-3xl font-extrabold text-rose-600">{{ $totalRevisi }}</span>
                <span class="text-xs text-slate-500">laporan</span>
            </div>
            <div class="mt-2 text-xs text-rose-500 font-medium">
                {{ $totalRevisi > 0 ? 'Perlu tindakan perbaikan segera' : 'Tidak ada laporan revisi' }}
            </div>
        </div>
    </div>

    <!-- Tabel 5 Laporan Terbaru -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 sm:px-6 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900">Laporan Kinerja Terbaru</h2>
                <p class="text-xs text-slate-500 mt-0.5">5 catatan aktivitas harian yang terakhir Anda inputkan.</p>
            </div>
            <a href="{{ route('staff.laporan.index') }}" class="text-xs font-semibold text-emerald-700 hover:text-emerald-800 flex items-center space-x-1">
                <span>Lihat Semua Laporan</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 font-semibold uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3">Tanggal & Waktu</th>
                        <th class="px-5 py-3">Uraian Kegiatan</th>
                        <th class="px-5 py-3">Output / Kuantitas</th>
                        <th class="px-5 py-3">Status Verifikasi</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($laporanTerbaru as $lap)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <div class="font-bold text-slate-900">{{ \Carbon\Carbon::parse($lap->tanggal_laporan)->format('d M Y') }}</div>
                                <div class="text-[11px] text-slate-500">{{ substr($lap->jam_mulai, 0, 5) }} - {{ substr($lap->jam_selesai, 0, 5) }} WIB</div>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="font-medium text-slate-800 max-w-md line-clamp-2">{{ $lap->uraian_kegiatan }}</div>
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <div class="font-semibold text-slate-900">{{ $lap->hasil_output }}</div>
                                <div class="text-[11px] text-slate-500">{{ (float) $lap->volume_kuantitas }} {{ $lap->satuan }}</div>
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                @if($lap->status_verifikasi === 'disetujui')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        <svg class="w-3 h-3 mr-1 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                        Disetujui
                                    </span>
                                @elseif($lap->status_verifikasi === 'revisi')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-rose-100 text-rose-800 border border-rose-200">
                                        <svg class="w-3 h-3 mr-1 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                        Perlu Revisi
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                        <svg class="w-3 h-3 mr-1 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
                                        Terkirim
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-right whitespace-nowrap space-x-1">
                                <a href="{{ route('staff.laporan.show', $lap) }}" class="inline-flex items-center px-2.5 py-1 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium transition">
                                    Detail
                                </a>
                                @if($lap->canBeEdited())
                                    <a href="{{ route('staff.laporan.edit', $lap) }}" class="inline-flex items-center px-2.5 py-1 rounded bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-medium transition">
                                        Edit
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-slate-400">
                                Belum ada laporan kinerja yang diinput. Klik tombol <strong>Input Kegiatan Hari Ini</strong> untuk mencatat aktivitas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
