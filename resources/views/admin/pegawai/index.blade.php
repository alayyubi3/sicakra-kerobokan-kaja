@extends('layouts.app')

@section('title', 'Manajemen Data Pegawai')

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900">Manajemen Data Pegawai</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Kelola data seluruh aparatur pegawai, NIP, seksi kerja, dan hak akses sistem.</p>
        </div>
        <a href="{{ route('admin.pegawai.create') }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-sm transition">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            Tambah Pegawai Baru
        </a>
    </div>

    <!-- Filter Card -->
    <div class="bg-white rounded-xl border border-slate-200/80 p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.pegawai.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <!-- Keyword -->
            <div class="lg:col-span-2">
                <label class="block text-[11px] font-semibold text-slate-600 uppercase mb-1">Cari Nama / NIP / Email</label>
                <div class="relative">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau NIP pegawai..."
                        class="w-full pl-9 pr-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>

            <!-- Role -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-600 uppercase mb-1">Peran (Role)</label>
                <select name="role" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">Semua Peran</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
                    <option value="staff" {{ request('role') === 'staff' ? 'selected' : '' }}>Staff Pegawai</option>
                </select>
            </div>

            <!-- Status & Button -->
            <div>
                <label class="block text-[11px] font-semibold text-slate-600 uppercase mb-1">Status Keaktifan</label>
                <div class="flex space-x-1.5">
                    <select name="status" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-1 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                    </select>
                    <button type="submit" class="px-3 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-xs font-semibold shadow-sm transition">
                        Filter
                    </button>
                    @if(request()->anyFilled(['q', 'role', 'status']))
                        <a href="{{ route('admin.pegawai.index') }}" class="px-2.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg text-xs font-medium transition" title="Reset filter">
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
                        <th class="px-5 py-3.5">Pegawai</th>
                        <th class="px-5 py-3.5">NIP & Email</th>
                        <th class="px-5 py-3.5">Jabatan & Seksi</th>
                        <th class="px-5 py-3.5">Peran</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5">Total Laporan</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($pegawaiList as $peg)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <div class="font-bold text-slate-900">{{ $peg->name }}</div>
                                @if($peg->id === auth()->id())
                                    <span class="inline-block mt-0.5 px-1.5 py-0.2 rounded text-[10px] bg-slate-200 text-slate-700 font-semibold">(Akun Anda)</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <div class="font-mono text-slate-800">{{ $peg->nip }}</div>
                                <div class="text-[11px] text-slate-400">{{ $peg->email }}</div>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="font-semibold text-slate-800">{{ $peg->jabatan }}</div>
                                <div class="text-[11px] text-slate-500">Seksi {{ $peg->bidang_seksi }}</div>
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold {{ $peg->role === 'admin' ? 'bg-amber-100 text-amber-800 border border-amber-300' : 'bg-slate-100 text-slate-800' }}">
                                    {{ strtoupper($peg->role) }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                @if($peg->status_aktif)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-800">
                                        ● Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-100 text-rose-800">
                                        ● Non-Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap font-bold text-slate-700">
                                {{ $peg->laporan_kinerjas_count }} Laporan
                            </td>
                            <td class="px-5 py-3.5 text-right whitespace-nowrap space-x-1">
                                <!-- Reset Password Button -->
                                <button type="button" onclick="openResetModal({{ $peg->id }}, '{{ addslashes($peg->name) }}')"
                                    class="inline-flex items-center px-2 py-1 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium transition" title="Reset Kata Sandi">
                                    Reset Password
                                </button>

                                <!-- Edit -->
                                <a href="{{ route('admin.pegawai.edit', $peg) }}" class="inline-flex items-center px-2 py-1 rounded bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-medium transition" title="Sunting Data">
                                    Edit
                                </a>

                                <!-- Toggle Status -->
                                @if($peg->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.pegawai.toggle-status', $peg) }}" class="inline" onsubmit="return confirm('Ubah status keaktifan akun {{ $peg->name }}?')">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-2 py-1 rounded {{ $peg->status_aktif ? 'bg-amber-50 hover:bg-amber-100 text-amber-700' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-700' }} font-medium transition">
                                            {{ $peg->status_aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>

                                    <!-- Delete -->
                                    <form method="POST" action="{{ route('admin.pegawai.destroy', $peg) }}" class="inline" onsubmit="return confirm('Hapus seluruh akun dan data riwayat {{ $peg->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-2 py-1 rounded bg-rose-50 hover:bg-rose-100 text-rose-700 font-medium transition">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center text-slate-400">
                                Tidak ada data pegawai yang sesuai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pegawaiList->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $pegawaiList->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Reset Password -->
<div id="reset-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200">
        <h3 class="text-base font-bold text-slate-900 mb-1">Reset Kata Sandi Pegawai</h3>
        <p class="text-xs text-slate-500 mb-4" id="modal-pegawai-name">Atur kata sandi baru untuk akun pegawai.</p>

        <form id="reset-form" method="POST" action="">
            @csrf
            <div class="mb-4">
                <label for="new_password" class="block text-xs font-semibold text-slate-700 uppercase mb-1">Kata Sandi Baru</label>
                <input type="password" id="new_password" name="new_password" required minlength="6" placeholder="Minimal 6 karakter"
                    class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            <div class="flex items-center justify-end space-x-2">
                <button type="button" onclick="closeResetModal()" class="px-3.5 py-2 border border-slate-300 rounded-lg text-xs font-medium text-slate-700 hover:bg-slate-50">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold shadow-sm">
                    Simpan Password Baru
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openResetModal(id, name) {
        document.getElementById('modal-pegawai-name').innerText = 'Atur kata sandi baru untuk akun: ' + name;
        document.getElementById('reset-form').action = '{{ url("admin/pegawai") }}/' + id + '/reset-password';
        document.getElementById('new_password').value = '';
        document.getElementById('reset-modal').classList.remove('hidden');
    }

    function closeResetModal() {
        document.getElementById('reset-modal').classList.add('hidden');
    }
</script>
@endsection
