@extends('layouts.app')

@section('title', 'Buku Arsip Laporan PDF')

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900">Buku Arsip Dokumen Laporan Kinerja</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Seluruh berkas PDF laporan resmi yang tersimpan otomatis di server kelurahan.</p>
        </div>
        <div>
            <button type="button" onclick="openGenerateModal()"
                class="inline-flex items-center px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-sm transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Generate & Arsipkan Dokumen Pegawai
            </button>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="bg-white rounded-xl border border-slate-200/80 p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.arsip.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <!-- Keyword -->
            <div class="lg:col-span-2">
                <label class="block text-[11px] font-semibold text-slate-600 uppercase mb-1">Cari Nomor Arsip / Nama Pegawai</label>
                <div class="relative">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Nomor arsip atau nama pegawai..."
                        class="w-full pl-9 pr-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>

            <!-- Pegawai -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-600 uppercase mb-1">Pegawai</label>
                <select name="user_id" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">Semua Pegawai</option>
                    @foreach($daftarPegawai as $p)
                        <option value="{{ $p->id }}" {{ request('user_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Bulan -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-600 uppercase mb-1">Bulan</label>
                <select name="bulan" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">Semua Bulan</option>
                    @foreach(\App\Services\LaporanPdfService::BULAN_INDO as $m => $nama)
                        <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Tahun & Button -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-600 uppercase mb-1">Tahun</label>
                <div class="flex space-x-1.5">
                    <select name="tahun" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">Semua Tahun</option>
                        @for($y = date('Y') - 2; $y <= date('Y') + 1; $y++)
                            <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                    <button type="submit" class="px-3 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-xs font-semibold shadow-sm transition">
                        Filter
                    </button>
                    @if(request()->anyFilled(['q', 'user_id', 'bulan', 'tahun']))
                        <a href="{{ route('admin.arsip.index') }}" class="px-2.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg text-xs font-medium transition" title="Reset filter">
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
                        <th class="px-5 py-3.5">Nomor Arsip Dokumen</th>
                        <th class="px-5 py-3.5">Nama Pegawai & NIP</th>
                        <th class="px-5 py-3.5">Periode Laporan</th>
                        <th class="px-5 py-3.5">Jenis</th>
                        <th class="px-5 py-3.5">Tanggal Diarsipkan</th>
                        <th class="px-5 py-3.5 text-right">Aksi Arsip</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($arsipList as $arsip)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <div class="font-mono font-bold text-emerald-800">{{ $arsip->nomor_arsip }}</div>
                                <div class="text-[10px] text-slate-400">{{ basename($arsip->file_path) }}</div>
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <div class="font-bold text-slate-900">{{ $arsip->user->name }}</div>
                                <div class="text-[11px] text-slate-500">NIP. {{ $arsip->user->nip }} • {{ $arsip->user->bidang_seksi }}</div>
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <div class="font-semibold text-slate-800">{{ $arsip->nama_bulan }} {{ $arsip->periode_tahun }}</div>
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $arsip->jenis_laporan === 'bulanan' ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $arsip->jenis_laporan }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap text-slate-600">
                                <div>{{ $arsip->created_at->translatedFormat('d M Y') }}</div>
                                <div class="text-[11px] text-slate-400">{{ $arsip->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td class="px-5 py-3.5 text-right whitespace-nowrap space-x-1">
                                <!-- Pratinjau / Print Langsung -->
                                <a href="{{ route('admin.arsip.preview', $arsip) }}" target="_blank"
                                    class="inline-flex items-center px-2.5 py-1 rounded bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-semibold transition" title="Lihat PDF di Tab Baru">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Pratinjau / Print
                                </a>

                                <!-- Unduh File -->
                                <a href="{{ route('admin.arsip.download', $arsip) }}"
                                    class="inline-flex items-center px-2.5 py-1 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold transition" title="Download File PDF">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    Unduh
                                </a>

                                <!-- Hapus -->
                                <form method="POST" action="{{ route('admin.arsip.destroy', $arsip) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus arsip fisik dan data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-2 py-1 rounded bg-rose-50 hover:bg-rose-100 text-rose-700 font-medium transition" title="Hapus Arsip">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400">
                                <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                                <span>Belum ada dokumen laporan yang diarsipkan di server. Dokumen akan otomatis masuk ke sini saat digenerate oleh Staff atau Admin.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($arsipList->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $arsipList->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Generate Arsip Manual oleh Admin -->
<div id="generate-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200">
        <h3 class="text-base font-bold text-slate-900 mb-1">Generate & Arsipkan Dokumen PDF</h3>
        <p class="text-xs text-slate-500 mb-4">Pilih pegawai dan periode untuk membukukan laporan ke server.</p>

        <form method="POST" action="{{ route('admin.arsip.generate-manual') }}" class="space-y-4">
            @csrf

            <div>
                <label for="modal-user" class="block text-xs font-semibold text-slate-700 uppercase mb-1">Pilih Pegawai <span class="text-rose-500">*</span></label>
                <select id="modal-user" name="user_id" required class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">-- Pilih Pegawai --</option>
                    @foreach($daftarPegawai as $p)
                        <option value="{{ $p->id }}">{{ $p->name }} (NIP. {{ $p->nip }})</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="modal-bulan" class="block text-xs font-semibold text-slate-700 uppercase mb-1">Bulan <span class="text-rose-500">*</span></label>
                    <select id="modal-bulan" name="bulan" required class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        @foreach(\App\Services\LaporanPdfService::BULAN_INDO as $m => $nama)
                            <option value="{{ $m }}" {{ date('n') == $m ? 'selected' : '' }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="modal-tahun" class="block text-xs font-semibold text-slate-700 uppercase mb-1">Tahun <span class="text-rose-500">*</span></label>
                    <select id="modal-tahun" name="tahun" required class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        @for($y = date('Y') - 2; $y <= date('Y') + 1; $y++)
                            <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="pt-1">
                <label class="inline-flex items-center text-xs text-slate-700 cursor-pointer">
                    <input type="checkbox" name="hanya_disetujui" value="1" checked class="h-4 w-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                    <span class="ml-2 font-medium">Hanya sertakan kegiatan yang berstatus disetujui</span>
                </label>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="closeGenerateModal()" class="px-3.5 py-2 border border-slate-300 rounded-lg text-xs font-medium text-slate-700 hover:bg-slate-50">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold shadow-sm">
                    Generate Dokumen
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openGenerateModal() {
        document.getElementById('generate-modal').classList.remove('hidden');
    }

    function closeGenerateModal() {
        document.getElementById('generate-modal').classList.add('hidden');
    }
</script>
@endsection
