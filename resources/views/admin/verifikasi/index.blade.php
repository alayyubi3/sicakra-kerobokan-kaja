@extends('layouts.app')

@section('title', 'Monitoring & Verifikasi Kinerja')

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900">Monitoring & Verifikasi Kinerja Pegawai</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Tinjau, evaluasi, dan sahkan laporan pelaksanaan tugas aparatur kelurahan.</p>
        </div>
        <div class="flex items-center space-x-2">
            <!-- Counter Badges -->
            <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                Antrean: {{ $countTerkirim }}
            </span>
            <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                Disetujui: {{ $countDisetujui }}
            </span>
            <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200">
                Revisi: {{ $countRevisi }}
            </span>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="bg-white rounded-xl border border-slate-200/80 p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.verifikasi.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
            <!-- Pegawai -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-600 uppercase mb-1">Pilih Pegawai</label>
                <select name="user_id" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">Semua Pegawai</option>
                    @foreach($daftarPegawai as $p)
                        <option value="{{ $p->id }}" {{ request('user_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Bidang / Seksi -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-600 uppercase mb-1">Bidang / Seksi</label>
                <select name="bidang_seksi" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">Semua Seksi</option>
                    @foreach($daftarBidang as $b)
                        <option value="{{ $b }}" {{ request('bidang_seksi') == $b ? 'selected' : '' }}>{{ $b }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Verifikasi -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-600 uppercase mb-1">Status Verifikasi</label>
                <select name="status" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">Semua Status</option>
                    <option value="terkirim" {{ request('status') === 'terkirim' ? 'selected' : '' }}>Terkirim (Menunggu)</option>
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

            <!-- Sampai Tanggal -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-600 uppercase mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            <!-- Kata Kunci & Button -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-600 uppercase mb-1">Cari Kegiatan</label>
                <div class="flex space-x-1.5">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Uraian..."
                        class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500">
                    <button type="submit" class="px-3 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-xs font-semibold shadow-sm transition">
                        Filter
                    </button>
                    @if(request()->anyFilled(['user_id', 'bidang_seksi', 'status', 'start_date', 'end_date', 'q']))
                        <a href="{{ route('admin.verifikasi.index') }}" class="px-2.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg text-xs font-medium transition" title="Reset filter">
                            ↺
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Form Bulk Approve Wrap -->
    <form id="bulk-form" method="POST" action="{{ route('admin.verifikasi.bulk-approve') }}">
        @csrf

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <!-- Table Header Control Bar -->
            <div class="p-4 bg-slate-50 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex items-center space-x-3">
                    <input type="checkbox" id="select-all" class="h-4 w-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500" onclick="toggleSelectAll(this)">
                    <label for="select-all" class="text-xs font-semibold text-slate-700 cursor-pointer">Pilih Semua di Halaman Ini</label>
                </div>
                <div>
                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menyetujui seluruh laporan yang dipilih sekaligus?')"
                        class="inline-flex items-center px-3.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-sm transition">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Setujui yang Dipilih Sekaligus
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
                    <thead class="bg-slate-50 text-slate-600 font-semibold uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3.5 w-8"></th>
                            <th class="px-4 py-3.5">Pegawai</th>
                            <th class="px-4 py-3.5">Tanggal & Waktu</th>
                            <th class="px-4 py-3.5">Uraian Tugas</th>
                            <th class="px-4 py-3.5">Output / Volume</th>
                            <th class="px-4 py-3.5">Bukti</th>
                            <th class="px-4 py-3.5">Status</th>
                            <th class="px-4 py-3.5 text-right">Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($laporanList as $lap)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="px-4 py-3.5">
                                    <input type="checkbox" name="selected_ids[]" value="{{ $lap->id }}" class="item-checkbox h-4 w-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                                </td>
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <div class="font-bold text-slate-900">{{ $lap->user->name }}</div>
                                    <div class="text-[11px] text-slate-500">NIP. {{ $lap->user->nip }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $lap->user->bidang_seksi }}</div>
                                </td>
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <div class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($lap->tanggal_laporan)->format('d M Y') }}</div>
                                    <div class="text-[11px] text-slate-500">{{ substr($lap->jam_mulai, 0, 5) }} - {{ substr($lap->jam_selesai, 0, 5) }} WIB</div>
                                </td>
                                <td class="px-4 py-3.5 max-w-xs">
                                    <div class="font-medium text-slate-800 line-clamp-2">{{ $lap->uraian_kegiatan }}</div>
                                    @if($lap->catatan_admin)
                                        <div class="mt-1 text-[11px] text-slate-600 italic bg-amber-50 p-1.5 rounded border border-amber-200">
                                            Catatan: "{{ $lap->catatan_admin }}"
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <div class="font-semibold text-slate-800">{{ $lap->hasil_output }}</div>
                                    <div class="text-[11px] text-slate-500">{{ (float) $lap->volume_kuantitas }} {{ $lap->satuan }}</div>
                                </td>
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    @if($lap->bukti_dukung_path)
                                        <a href="{{ asset('storage/' . $lap->bukti_dukung_path) }}" target="_blank" class="inline-flex items-center text-emerald-600 hover:text-emerald-800 font-semibold space-x-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            <span>Berkas</span>
                                        </a>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    @if($lap->status_verifikasi === 'disetujui')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            Disetujui
                                        </span>
                                    @elseif($lap->status_verifikasi === 'revisi')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-100 text-rose-800 border border-rose-200">
                                            Perlu Revisi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                            Terkirim
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-right whitespace-nowrap space-x-1">
                                    <a href="{{ route('admin.verifikasi.show', $lap) }}" class="inline-flex items-center px-2.5 py-1 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium transition" title="Periksa Rinci">
                                        Periksa
                                    </a>

                                    <!-- Quick Modal Trigger -->
                                    <button type="button" onclick="openVerifModal({{ $lap->id }}, '{{ addslashes($lap->user->name) }}', '{{ $lap->status_verifikasi }}', '{{ addslashes($lap->catatan_admin ?? '') }}')"
                                        class="inline-flex items-center px-2.5 py-1 rounded bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-medium transition">
                                        Ubah Status
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-12 text-center text-slate-400">
                                    Tidak ada laporan kinerja yang ditemukan berdasarkan kriteria pencarian.
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
    </form>
</div>

<!-- Modal Quick Verifikasi -->
<div id="verif-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200">
        <h3 class="text-base font-bold text-slate-900 mb-1">Verifikasi Laporan Kinerja</h3>
        <p class="text-xs text-slate-500 mb-4" id="modal-verif-user">Tentukan status persetujuan laporan.</p>

        <form id="verif-form" method="POST" action="">
            @csrf
            <div class="mb-4">
                <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Status Verifikasi</label>
                <select id="modal-status-select" name="status_verifikasi" required onchange="toggleCatatanRequired(this.value)"
                    class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="disetujui">Disetujui (Sahkan Kegiatan)</option>
                    <option value="revisi">Perlu Revisi (Kembalikan ke Pegawai)</option>
                    <option value="terkirim">Terkirim (Menunggu Evaluasi)</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="modal-catatan" class="block text-xs font-semibold text-slate-700 uppercase mb-1">
                    Catatan Evaluasi / Instruksi Revisi
                    <span id="catatan-star" class="text-rose-500 hidden">*</span>
                </label>
                <textarea id="modal-catatan" name="catatan_admin" rows="3" placeholder="Tuliskan alasan revisi atau catatan apresiasi untuk pegawai..."
                    class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
            </div>

            <div class="flex items-center justify-end space-x-2">
                <button type="button" onclick="closeVerifModal()" class="px-3.5 py-2 border border-slate-300 rounded-lg text-xs font-medium text-slate-700 hover:bg-slate-50">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold shadow-sm">
                    Simpan Verifikasi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleSelectAll(source) {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        for (let i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = source.checked;
        }
    }

    function openVerifModal(id, userName, currentStatus, currentNotes) {
        document.getElementById('modal-verif-user').innerText = 'Pegawai: ' + userName;
        document.getElementById('verif-form').action = '{{ url("admin/verifikasi") }}/' + id;
        document.getElementById('modal-status-select').value = currentStatus;
        document.getElementById('modal-catatan').value = currentNotes;
        toggleCatatanRequired(currentStatus);
        document.getElementById('verif-modal').classList.remove('hidden');
    }

    function closeVerifModal() {
        document.getElementById('verif-modal').classList.add('hidden');
    }

    function toggleCatatanRequired(val) {
        const star = document.getElementById('catatan-star');
        const catatan = document.getElementById('modal-catatan');
        if (val === 'revisi') {
            star.classList.remove('hidden');
            catatan.required = true;
        } else {
            star.classList.add('hidden');
            catatan.required = false;
        }
    }
</script>
@endsection
