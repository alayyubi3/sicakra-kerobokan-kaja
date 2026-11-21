@extends('layouts.app')

@section('title', 'Sunting Pegawai')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Breadcrumb & Header -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-emerald-700">Dashboard</a>
                <span>/</span>
                <a href="{{ route('admin.pegawai.index') }}" class="hover:text-emerald-700">Pegawai</a>
                <span>/</span>
                <span class="text-slate-800 font-semibold">Sunting</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900">Perbarui Data Pegawai</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Ubah rincian profil kepegawaian, jabatan, dan status akun.</p>
        </div>
        <a href="{{ route('admin.pegawai.index') }}" class="inline-flex items-center px-3 py-1.5 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 text-slate-600 text-xs font-medium transition">
            Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.pegawai.update', $pegawai) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Nama Lengkap -->
            <div>
                <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Nama Lengkap & Gelar <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="name" name="name" required value="{{ old('name', $pegawai->name) }}"
                    class="w-full px-3 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>

            <!-- NIP & Email Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="nip" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        NIP (Nomor Induk Pegawai) <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="nip" name="nip" required value="{{ old('nip', $pegawai->nip) }}"
                        class="w-full px-3 py-2.5 text-sm font-mono border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Alamat Email Resmi <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" required value="{{ old('email', $pegawai->email) }}"
                        class="w-full px-3 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>

            <!-- Jabatan & Bidang/Seksi Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="jabatan" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Jabatan <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="jabatan" name="jabatan" required value="{{ old('jabatan', $pegawai->jabatan) }}"
                        class="w-full px-3 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <div>
                    <label for="bidang_seksi" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Bidang / Seksi Kerja <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="bidang_seksi" name="bidang_seksi" required value="{{ old('bidang_seksi', $pegawai->bidang_seksi) }}"
                        class="w-full px-3 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>

            <!-- Peran & Status Keaktifan Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <label for="role" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Peran Akun <span class="text-rose-500">*</span>
                    </label>
                    <select id="role" name="role" required class="w-full px-3 py-2.5 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="staff" {{ old('role', $pegawai->role) === 'staff' ? 'selected' : '' }}>Staff Pegawai</option>
                        <option value="admin" {{ old('role', $pegawai->role) === 'admin' ? 'selected' : '' }}>Administrator</option>
                    </select>
                </div>

                <div class="flex items-center pt-6">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="status_aktif" value="1" {{ old('status_aktif', $pegawai->status_aktif) ? 'checked' : '' }}
                            class="h-4 w-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                        <span class="ml-2.5 text-xs font-semibold text-slate-700">Status Akun Aktif</span>
                    </label>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="border-t border-slate-200 pt-5 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.pegawai.index') }}" class="px-4 py-2.5 border border-slate-300 rounded-xl text-slate-700 text-xs font-medium hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold shadow-md transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Perubahan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
