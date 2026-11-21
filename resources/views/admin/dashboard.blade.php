@extends('layouts.app')

@section('title', 'Dashboard Administrator Kelurahan')

@section('content')
<div class="space-y-6">

    <!-- Header & Welcome Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-emerald-950 rounded-2xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="inline-flex items-center space-x-2 bg-emerald-500/20 px-3 py-1 rounded-full text-xs text-emerald-300 mb-3 border border-emerald-500/30">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>Panel Pengawasan & Evaluasi Kinerja Pegawai</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Dashboard Administrator Kelurahan</h1>
                <p class="text-slate-300 text-sm mt-1 max-w-2xl">
                    Pantau produktivitas harian pegawai, status verifikasi laporan kegiatan, dan tertib administrasi kantor.
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.verifikasi.index') }}" class="inline-flex items-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs sm:text-sm rounded-xl shadow-md transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Verifikasi Laporan
                    @if($totalMenungguVerifikasi > 0)
                        <span class="ml-2 px-1.5 py-0.5 rounded-full text-[10px] bg-amber-400 text-slate-950 font-bold">
                            {{ $totalMenungguVerifikasi }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('admin.arsip.index') }}" class="inline-flex items-center px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 font-semibold text-xs sm:text-sm rounded-xl border border-slate-700 transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                    Buku Arsip PDF
                </a>
            </div>
        </div>
    </div>

    <!-- 4 Key Statistic Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Laporan Masuk Hari Ini -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Laporan Hari Ini</span>
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            </div>
            <div class="mt-3 flex items-baseline space-x-2">
                <span class="text-3xl font-extrabold text-slate-900">{{ $totalLaporanHariIni }}</span>
                <span class="text-xs text-slate-500">kegiatan diinput</span>
            </div>
            <div class="mt-2 text-xs text-blue-600 font-medium">
                {{ $today->translatedFormat('d F Y') }}
            </div>
        </div>

        <!-- Laporan Bulan Ini -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-700">Laporan Bulan Ini</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
            </div>
            <div class="mt-3 flex items-baseline space-x-2">
                <span class="text-3xl font-extrabold text-emerald-600">{{ $totalLaporanBulanIni }}</span>
                <span class="text-xs text-slate-500">total aktivitas</span>
            </div>
            <div class="mt-2 text-xs text-slate-500">
                Sah: <strong class="text-emerald-700">{{ $disetujuiCount }}</strong> | Revisi: <strong class="text-rose-600">{{ $revisiCount }}</strong>
            </div>
        </div>

        <!-- Menunggu Verifikasi -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-amber-700">Antrean Verifikasi</span>
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-3 flex items-baseline space-x-2">
                <span class="text-3xl font-extrabold text-amber-600">{{ $totalMenungguVerifikasi }}</span>
                <span class="text-xs text-slate-500">perlu diperiksa</span>
            </div>
            <div class="mt-2 text-xs text-amber-700 font-medium">
                {{ $totalMenungguVerifikasi > 0 ? 'Menunggu persetujuan pimpinan' : 'Seluruh laporan terverifikasi' }}
            </div>
        </div>

        <!-- Total Pegawai Aktif -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Staff Aktif</span>
                <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
            <div class="mt-3 flex items-baseline space-x-2">
                <span class="text-3xl font-extrabold text-slate-900">{{ $totalStaffAktif }}</span>
                <span class="text-xs text-slate-500">pegawai terdaftar</span>
            </div>
            <div class="mt-2 text-xs text-slate-400">
                {{ $activeKop->nama_kelurahan ?? 'Kelurahan Kerobokan Kaja' }}
            </div>
        </div>
    </div>

    <!-- Section Middle: 2 Columns (Grafik Keaktifan Seksi & Pegawai Belum Lapor Hari Ini) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Kolom 1 & 2: Grafik Keaktifan Pegawai per Seksi/Bidang -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Keaktifan Pelaporan per Seksi / Bidang</h2>
                    <p class="text-xs text-slate-500">Distribusi jumlah kegiatan kinerja yang masuk pada bulan ini.</p>
                </div>
                <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700">
                    Bulan {{ now()->translatedFormat('F Y') }}
                </span>
            </div>

            <div class="space-y-4 mt-6">
                @php
                    $maxLaporan = $statistikBidang->max('total_laporan') ?: 1;
                @endphp

                @forelse($statistikBidang as $bidang)
                    @php
                        $percentage = round(($bidang->total_laporan / $maxLaporan) * 100);
                    @endphp
                    <div>
                        <div class="flex justify-between items-center text-xs font-semibold mb-1">
                            <span class="text-slate-800">{{ $bidang->bidang_seksi ?: 'Tanpa Bidang / Lainnya' }}</span>
                            <span class="text-emerald-700 font-bold">{{ $bidang->total_laporan }} Laporan</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                            <div class="bg-gradient-to-r from-emerald-600 to-teal-500 h-3 rounded-full transition-all duration-500" style="width: {{ max($percentage, 8) }}%;"></div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400 text-xs">
                        Belum ada data aktivitas laporan kinerja pada bulan ini.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Kolom 3: Pegawai yang Belum Mengisi Laporan Hari Ini -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Belum Mengisi Hari Ini</h2>
                    <p class="text-xs text-slate-500">Daftar staf yang belum menginput tugas hari ini.</p>
                </div>
                <span class="w-6 h-6 rounded-full bg-rose-100 text-rose-700 text-xs font-bold flex items-center justify-center">
                    {{ $pegawaiBelumLaporHariIni->count() }}
                </span>
            </div>

            <div class="divide-y divide-slate-100 mt-2 max-h-72 overflow-y-auto">
                @forelse($pegawaiBelumLaporHariIni as $peg)
                    <div class="py-3 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-xs uppercase">
                                {{ substr($peg->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="text-xs font-bold text-slate-800">{{ $peg->name }}</div>
                                <div class="text-[11px] text-slate-400">{{ $peg->bidang_seksi ?? $peg->jabatan }}</div>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-rose-50 text-rose-600 border border-rose-200">
                            Nihil
                        </span>
                    </div>
                @empty
                    <div class="text-center py-8 text-emerald-600 text-xs font-medium">
                        <svg class="w-8 h-8 mx-auto text-emerald-500 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Luar biasa! Seluruh pegawai aktif telah mencatat laporan kinerja hari ini.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Section Bottom: 5 Antrean Laporan Membutuhkan Verifikasi -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 sm:px-6 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900">Antrean Verifikasi Laporan Kinerja</h2>
                <p class="text-xs text-slate-500 mt-0.5">Laporan terbaru dari pegawai yang menunggu verifikasi dari Admin / Lurah.</p>
            </div>
            <a href="{{ route('admin.verifikasi.index') }}" class="text-xs font-semibold text-emerald-700 hover:text-emerald-800 flex items-center space-x-1">
                <span>Buka Panel Verifikasi Lengkap</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 font-semibold uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">Pegawai</th>
                        <th class="px-5 py-3.5">Tanggal & Waktu</th>
                        <th class="px-5 py-3.5">Uraian Kegiatan</th>
                        <th class="px-5 py-3.5">Output / Volume</th>
                        <th class="px-5 py-3.5 text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($laporanAntreanVerifikasi as $antrean)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <div class="font-bold text-slate-900">{{ $antrean->user->name }}</div>
                                <div class="text-[11px] text-slate-500">NIP. {{ $antrean->user->nip }} • {{ $antrean->user->bidang_seksi }}</div>
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <div class="font-semibold text-slate-800">{{ \Carbon\Carbon::parse($antrean->tanggal_laporan)->format('d M Y') }}</div>
                                <div class="text-[11px] text-slate-500">{{ substr($antrean->jam_mulai, 0, 5) }} - {{ substr($antrean->jam_selesai, 0, 5) }} WIB</div>
                            </td>
                            <td class="px-5 py-3.5 max-w-sm">
                                <div class="font-medium text-slate-800 line-clamp-2">{{ $antrean->uraian_kegiatan }}</div>
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <div class="font-semibold text-slate-800">{{ $antrean->hasil_output }}</div>
                                <div class="text-[11px] text-slate-500">{{ (float) $antrean->volume_kuantitas }} {{ $antrean->satuan }}</div>
                            </td>
                            <td class="px-5 py-3.5 text-right whitespace-nowrap space-x-1">
                                <a href="{{ route('admin.verifikasi.show', $antrean) }}" class="inline-flex items-center px-3 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-medium transition shadow-sm">
                                    Periksa & Verifikasi
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-slate-400">
                                Saat ini tidak ada laporan yang sedang menunggu verifikasi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
