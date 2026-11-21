@extends('layouts.app')

@section('title', 'Cetak Laporan Kinerja PDF')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900">Cetak & Unduh Laporan Kinerja</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Generate dokumen PDF resmi berstandar A4 lengkap dengan Kop Surat Kelurahan dan tanda tangan Lurah.</p>
        </div>
    </div>

    <!-- Parameter Filter Selector Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm">
        <form method="GET" action="{{ route('staff.cetak.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Mode Filter -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Periode Laporan</label>
                    <select name="mode" id="mode-selector" onchange="toggleFilterMode(this.value)"
                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="bulanan" {{ $mode === 'bulanan' ? 'selected' : '' }}>Laporan Bulanan</option>
                        <option value="rentang" {{ $mode === 'rentang' ? 'selected' : '' }}>Rentang Tanggal Khusus</option>
                    </select>
                </div>

                <!-- Bulan & Tahun (Jika Mode Bulanan) -->
                <div id="bulan-container" class="{{ $mode === 'rentang' ? 'hidden' : '' }}">
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Bulan</label>
                    <select name="bulan" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500">
                        @foreach([1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'] as $m => $nama)
                            <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="tahun-container" class="{{ $mode === 'rentang' ? 'hidden' : '' }}">
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Tahun</label>
                    <select name="tahun" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500">
                        @for($y = date('Y') - 2; $y <= date('Y') + 1; $y++)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <!-- Rentang Tanggal (Jika Mode Rentang) -->
                <div id="start-date-container" class="{{ $mode === 'bulanan' ? 'hidden' : '' }}">
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ $startDate ?: date('Y-m-01') }}"
                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <div id="end-date-container" class="{{ $mode === 'bulanan' ? 'hidden' : '' }}">
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ $endDate ?: date('Y-m-d') }}"
                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <!-- Hanya Disetujui Toggle & Filter Button -->
                <div class="flex flex-col justify-end space-y-2">
                    <label class="inline-flex items-center text-xs text-slate-700 cursor-pointer">
                        <input type="checkbox" name="hanya_disetujui" value="1" {{ $hanyaDisetujui ? 'checked' : '' }}
                            class="h-4 w-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                        <span class="ml-2 font-medium">Hanya yang disetujui</span>
                    </label>

                    <button type="submit" class="w-full py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-xs font-semibold shadow-sm transition">
                        Muat Data
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Preview & Action Bar -->
    <div class="bg-emerald-900 text-white rounded-2xl p-5 shadow-md flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="text-xs uppercase tracking-wider text-emerald-300 font-semibold">Status Data Periode Ini</div>
            <div class="text-lg font-bold mt-0.5">
                Ditemukan {{ $laporanList->count() }} Kegiatan Kinerja
            </div>
            <p class="text-xs text-emerald-200 mt-0.5">
                Mengunduh PDF akan otomatis mendokumentasikan laporan ke buku <strong>Arsip Laporan Kelurahan</strong>.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <!-- Pratinjau / Print Langsung di Tab Baru -->
            <a href="{{ route('staff.cetak.preview', request()->query()) }}" target="_blank"
                class="inline-flex items-center px-4 py-2.5 rounded-xl bg-white text-emerald-900 font-bold text-xs shadow hover:bg-emerald-50 transition">
                <svg class="w-4 h-4 mr-1.5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                Pratinjau / Cetak (Print)
            </a>

            <!-- Download PDF & Simpan ke Arsip Otomatis -->
            <a href="{{ route('staff.cetak.download', request()->query()) }}"
                class="inline-flex items-center px-5 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-white font-bold text-xs shadow hover:shadow-lg transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Download PDF Resmi
            </a>
        </div>
    </div>

    <!-- Live Document Preview Container (Sheet-style Simulation) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-10 max-w-4xl mx-auto overflow-x-auto">
        <div class="min-w-[650px] text-slate-800">

            <!-- KOP SURAT PREVIEW -->
            <div class="border-b-4 border-double border-slate-900 pb-3 mb-6">
                <div class="flex items-center">
                    <div class="w-20 flex-shrink-0 text-center">
                        @if($kop && $kop->logo_path)
                            <img src="{{ asset('storage/' . $kop->logo_path) }}" alt="Logo" class="max-h-16 max-w-16 mx-auto object-contain">
                        @else
                            <div class="w-14 h-14 mx-auto rounded-lg border-2 border-emerald-700 flex items-center justify-center font-bold text-emerald-800 text-lg">
                                RI
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 text-center pr-12">
                        <div class="font-bold text-xs uppercase tracking-wider text-slate-700">{{ $kop->nama_pemerintah ?? 'PEMERINTAH KABUPATEN BADUNG' }}</div>
                        <div class="font-bold text-sm uppercase tracking-wider text-slate-800">{{ $kop->nama_kecamatan ?? 'KECAMATAN KUTA UTARA' }}</div>
                        <div class="font-extrabold text-lg uppercase tracking-wide text-emerald-800">{{ $kop->nama_kelurahan ?? 'KELURAHAN KEROBOKAN KAJA' }}</div>
                        <div class="text-[11px] text-slate-600">{{ $kop->alamat_kantor ?? 'Jl. Raya Kerobokan No. 100, Kerobokan Kaja, Kec. Kuta Utara, Kabupaten Badung, Bali 80361' }}</div>
                        @if($kop && $kop->kontak_kantor)
                            <div class="text-[10px] text-slate-500">{{ $kop->kontak_kantor }}</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- JUDUL PREVIEW -->
            <div class="text-center mb-6">
                <h3 class="text-sm font-extrabold uppercase underline tracking-wider text-slate-900">LAPORAN KINERJA DAN CAPAIAN TUGAS PEGAWAI</h3>
                <div class="text-xs text-slate-500 mt-0.5">
                    Periode:
                    @if($mode === 'bulanan')
                        Bulan {{ \App\Services\LaporanPdfService::BULAN_INDO[$bulan] ?? $bulan }} Tahun {{ $tahun }}
                    @else
                        {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d/m/Y') : '-' }} s/d {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d/m/Y') : '-' }}
                    @endif
                </div>
            </div>

            <!-- DATA PEGAWAI PREVIEW -->
            <div class="text-xs mb-4 space-y-1 bg-slate-50 p-3 rounded-lg border border-slate-200">
                <div class="grid grid-cols-6">
                    <span class="col-span-2 text-slate-500 font-medium">Nama Pegawai</span>
                    <span class="col-span-4 font-bold text-slate-900">: {{ $user->name }}</span>
                </div>
                <div class="grid grid-cols-6">
                    <span class="col-span-2 text-slate-500 font-medium">NIP</span>
                    <span class="col-span-4 font-bold text-slate-900">: {{ $user->nip }}</span>
                </div>
                <div class="grid grid-cols-6">
                    <span class="col-span-2 text-slate-500 font-medium">Jabatan / Seksi</span>
                    <span class="col-span-4 font-bold text-slate-900">: {{ $user->jabatan }} / {{ $user->bidang_seksi }}</span>
                </div>
            </div>

            <!-- TABEL KINERJA PREVIEW -->
            <table class="w-full text-xs border border-slate-300 divide-y divide-slate-300 mb-6">
                <thead class="bg-slate-100 font-bold text-slate-800 text-center">
                    <tr>
                        <th class="border border-slate-300 p-2 w-10">No</th>
                        <th class="border border-slate-300 p-2 w-28">Hari / Tanggal</th>
                        <th class="border border-slate-300 p-2 w-24">Waktu</th>
                        <th class="border border-slate-300 p-2">Uraian Kegiatan</th>
                        <th class="border border-slate-300 p-2 w-36">Output</th>
                        <th class="border border-slate-300 p-2 w-20">Volume</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($laporanList as $idx => $item)
                        <tr class="hover:bg-slate-50">
                            <td class="border border-slate-300 p-2 text-center text-slate-500">{{ $idx + 1 }}</td>
                            <td class="border border-slate-300 p-2">
                                <div class="font-bold">{{ \Carbon\Carbon::parse($item->tanggal_laporan)->format('d/m/Y') }}</div>
                                <div class="text-[10px] text-slate-500">{{ \Carbon\Carbon::parse($item->tanggal_laporan)->translatedFormat('l') }}</div>
                            </td>
                            <td class="border border-slate-300 p-2 text-center">
                                {{ substr($item->jam_mulai, 0, 5) }} - {{ substr($item->jam_selesai, 0, 5) }}
                            </td>
                            <td class="border border-slate-300 p-2 leading-relaxed">
                                {{ $item->uraian_kegiatan }}
                            </td>
                            <td class="border border-slate-300 p-2">
                                {{ $item->hasil_output }}
                            </td>
                            <td class="border border-slate-300 p-2 text-center font-bold">
                                {{ (float) $item->volume_kuantitas }} {{ $item->satuan }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="border border-slate-300 p-6 text-center text-slate-400 italic">
                                Belum ada catatan kinerja pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- TANDA TANGAN PREVIEW -->
            <div class="grid grid-cols-2 text-xs text-center pt-4">
                <div>
                    <div>Mengetahui,</div>
                    <div class="font-bold">{{ $kop->jabatan_penandatangan ?? 'Lurah Kerobokan Kaja' }}</div>
                    <div class="h-16"></div>
                    <div class="font-bold underline">{{ $kop->nama_lurah ?? 'I Gusti Ngurah Agung, S.STP., M.Si.' }}</div>
                    <div class="text-[11px] text-slate-500">NIP. {{ $kop->nip_lurah ?? '19850412 201001 1 012' }}</div>
                </div>

                <div>
                    <div>{{ $kop && $kop->nama_kelurahan ? ucwords(strtolower(str_replace('KELURAHAN ', '', $kop->nama_kelurahan))) : 'Kerobokan Kaja' }}, {{ now()->translatedFormat('d F Y') }}</div>
                    <div class="font-bold">Pegawai yang Bersangkutan,</div>
                    <div class="h-16"></div>
                    <div class="font-bold underline">{{ $user->name }}</div>
                    <div class="text-[11px] text-slate-500">NIP. {{ $user->nip }}</div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function toggleFilterMode(val) {
        const isRentang = val === 'rentang';
        document.getElementById('bulan-container').classList.toggle('hidden', isRentang);
        document.getElementById('tahun-container').classList.toggle('hidden', isRentang);
        document.getElementById('start-date-container').classList.toggle('hidden', !isRentang);
        document.getElementById('end-date-container').classList.toggle('hidden', !isRentang);
    }
</script>
@endsection
