@extends('layouts.app')

@section('title', 'Daftar Laporan Kinerja')

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900">Riwayat Laporan Kinerja</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Kelola dan pantau seluruh catatan pelaksanaan tugas harian Anda.</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('staff.cetak.index') }}" class="inline-flex items-center px-3.5 py-2 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-sm transition">
                <svg class="w-4 h-4 mr-1.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Rekap
            </a>
            <a href="{{ route('staff.laporan.create') }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-sm transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Catat Kegiatan Baru
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="bg-white rounded-xl border border-slate-200/80 p-4 shadow-sm">
        <form method="GET" action="{{ route('staff.laporan.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <!-- Keyword -->
            <div class="lg:col-span-2">
                <label class="block text-[11px] font-semibold text-slate-600 uppercase mb-1">Cari Kegiatan / Output</label>
                <div class="relative">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari uraian atau output..."
                        class="w-full pl-9 pr-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-600 uppercase mb-1">Status Verifikasi</label>
                <select name="status" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">Semua Status</option>
                    <option value="terkirim" {{ request('status') === 'terkirim' ? 'selected' : '' }}>Terkirim</option>
                    <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="revisi" {{ request('status') === 'revisi' ? 'selected' : '' }}>Perlu Revisi</option>
                </select>
            </div>

            <!-- Dari Tanggal -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-600 uppercase mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            <!-- Sampai Tanggal & Button -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-600 uppercase mb-1">Sampai Tanggal</label>
                <div class="flex space-x-1.5">
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500">
                    <button type="submit" class="px-3 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-xs font-semibold shadow-sm transition">
                        Filter
                    </button>
                    @if(request()->anyFilled(['q', 'status', 'start_date', 'end_date']))
                        <a href="{{ route('staff.laporan.index') }}" class="px-2.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg text-xs font-medium transition" title="Reset filter">
                            ↺
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 font-semibold uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">No</th>
                        <th class="px-5 py-3.5">Tanggal & Waktu</th>
                        <th class="px-5 py-3.5">Uraian Kegiatan</th>
                        <th class="px-5 py-3.5">Hasil / Output</th>
                        <th class="px-5 py-3.5">Volume</th>
                        <th class="px-5 py-3.5">Bukti</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($laporanList as $index => $lap)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-5 py-3.5 text-slate-400 font-medium">
                                {{ $laporanList->firstItem() + $index }}
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <div class="font-bold text-slate-900">{{ \Carbon\Carbon::parse($lap->tanggal_laporan)->format('d M Y') }}</div>
                                <div class="text-[11px] text-slate-500">{{ substr($lap->jam_mulai, 0, 5) }} - {{ substr($lap->jam_selesai, 0, 5) }} WIB</div>
                            </td>
                            <td class="px-5 py-3.5 max-w-xs">
                                <div class="font-medium text-slate-800 line-clamp-2">{{ $lap->uraian_kegiatan }}</div>
                                @if($lap->catatan_admin)
                                    <div class="mt-1 text-[11px] text-rose-600 italic">
                                        Catatan Pimpinan: "{{ $lap->catatan_admin }}"
                                    </div>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <span class="font-semibold text-slate-900">{{ $lap->hasil_output }}</span>
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap font-medium text-slate-800">
                                {{ (float) $lap->volume_kuantitas }} {{ $lap->satuan }}
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                @if($lap->bukti_dukung_path)
                                    <a href="{{ asset('storage/' . $lap->bukti_dukung_path) }}" target="_blank" class="inline-flex items-center text-emerald-600 hover:text-emerald-800 font-medium space-x-1" title="Lihat Berkas Bukti">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                        <span>Lihat</span>
                                    </a>
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                @if($lap->status_verifikasi === 'disetujui')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        Disetujui
                                    </span>
                                @elseif($lap->status_verifikasi === 'revisi')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-100 text-rose-800 border border-rose-200">
                                        Revisi
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                        Terkirim
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-right whitespace-nowrap space-x-1">
                                <a href="{{ route('staff.laporan.show', $lap) }}" class="inline-flex items-center px-2.5 py-1 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium transition" title="Lihat Rincian">
                                    Detail
                                </a>

                                @if($lap->canBeEdited())
                                    <a href="{{ route('staff.laporan.edit', $lap) }}" class="inline-flex items-center px-2.5 py-1 rounded bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-medium transition" title="Ubah Laporan">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('staff.laporan.destroy', $lap) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan kegiatan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-2.5 py-1 rounded bg-rose-50 hover:bg-rose-100 text-rose-700 font-medium transition" title="Hapus Laporan">
                                            Hapus
                                        </button>
                                    </form>
                                @else
                                    <span class="inline-block px-2 py-1 text-[10px] text-slate-400 bg-slate-100 rounded" title="Laporan sudah disetujui, tidak dapat diubah">Terkunci</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center text-slate-400">
                                <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <span>Tidak ada laporan kinerja yang cocok dengan filter.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($laporanList->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $laporanList->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
