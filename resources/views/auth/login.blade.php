@extends('layouts.auth')

@section('title', 'Masuk ke Sistem SICAKRA')

@section('content')
<div class="sm:mx-auto sm:w-full sm:max-w-md px-4">
    <!-- Header Logo & Identity -->
    <div class="text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-emerald-600 text-white shadow-xl shadow-emerald-900/50 mb-3 border border-emerald-400/30">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        </div>
        <h1 class="text-2xl font-bold tracking-tight text-white">SICAKRA</h1>
        <p class="text-sm text-slate-300 mt-1 font-medium">Sistem Informasi Pelaporan Kinerja Pegawai</p>
        <p class="text-xs text-emerald-400 font-semibold tracking-wide uppercase mt-0.5">{{ $activeKop->nama_kelurahan ?? 'KELURAHAN KEROBOKAN KAJA' }}</p>
        <p class="text-[11px] text-slate-400 font-medium tracking-wide uppercase">{{ $activeKop->nama_kecamatan ?? 'KECAMATAN KUTA UTARA' }} - {{ $activeKop->nama_pemerintah ?? 'PEMERINTAH KABUPATEN BADUNG' }}</p>
    </div>

    <!-- Card Form -->
    <div class="mt-8 bg-white/95 backdrop-blur-sm py-8 px-6 shadow-2xl rounded-2xl sm:px-10 border border-slate-200/50">
        <!-- Flash Alert -->
        @if(session('info'))
            <div class="mb-5 rounded-lg bg-blue-50 border border-blue-200 p-3 text-xs text-blue-800 flex items-center space-x-2">
                <svg class="w-4 h-4 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-5 rounded-lg bg-rose-50 border border-rose-200 p-3 text-xs text-rose-800">
                <div class="font-semibold mb-1">Gagal Masuk:</div>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- NIP / Email Input -->
            <div>
                <label for="login" class="block text-xs font-semibold uppercase tracking-wider text-slate-700">
                    NIP atau Alamat Email
                </label>
                <div class="mt-1.5 relative rounded-lg shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <input id="login" name="login" type="text" autocomplete="username" required
                        value="{{ old('login') }}"
                        placeholder="Contoh: 198501152008011002 atau admin@sicakra.id"
                        class="block w-full pl-10 pr-3 py-2.5 text-sm border border-slate-300 rounded-lg text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                </div>
            </div>

            <!-- Password Input -->
            <div>
                <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-700">
                    Kata Sandi
                </label>
                <div class="mt-1.5 relative rounded-lg shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                        placeholder="••••••••"
                        class="block w-full pl-10 pr-3 py-2.5 text-sm border border-slate-300 rounded-lg text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                </div>
            </div>

            <!-- Remember me & help -->
            <div class="flex items-center justify-between text-xs">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox"
                        class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-slate-300 rounded">
                    <label for="remember" class="ml-2 block text-slate-700 font-medium">Ingat saya di perangkat ini</label>
                </div>
                <span class="text-slate-400">Aman & Terenkripsi</span>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit"
                    class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-lg shadow-md text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    Masuk ke Sistem
                </button>
            </div>
        </form>

        <!-- Quick Fill Helper for Demo / Testing -->
        <div class="mt-6 pt-5 border-t border-slate-200">
            <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-2.5 text-center">Akun Demo (Klik untuk Isi Otomatis)</p>
            <div class="grid grid-cols-1 gap-2">
                <button type="button" onclick="fillLogin('198501152008011002', 'password')"
                    class="w-full text-left px-3 py-2 rounded-lg bg-amber-50 hover:bg-amber-100 border border-amber-200 transition text-xs flex items-center justify-between">
                    <div>
                        <div class="font-bold text-amber-900">👑 Admin (Lurah / Sekkel)</div>
                        <div class="text-[11px] text-amber-700">NIP: 198501152008011002</div>
                    </div>
                    <span class="text-[10px] bg-amber-200/80 text-amber-900 px-1.5 py-0.5 rounded font-mono font-bold">Pass: password</span>
                </button>

                <button type="button" onclick="fillLogin('199203142019021001', 'password')"
                    class="w-full text-left px-3 py-2 rounded-lg bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 transition text-xs flex items-center justify-between">
                    <div>
                        <div class="font-bold text-emerald-900">👤 Staff 1 - Ahmad Fauzi</div>
                        <div class="text-[11px] text-emerald-700">NIP: 199203142019021001</div>
                    </div>
                    <span class="text-[10px] bg-emerald-200/80 text-emerald-900 px-1.5 py-0.5 rounded font-mono font-bold">Pass: password</span>
                </button>

                <button type="button" onclick="fillLogin('199507202020122003', 'password')"
                    class="w-full text-left px-3 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 border border-slate-300 transition text-xs flex items-center justify-between">
                    <div>
                        <div class="font-bold text-slate-800">👤 Staff 2 - Siti Rahmawati</div>
                        <div class="text-[11px] text-slate-600">NIP: 199507202020122003</div>
                    </div>
                    <span class="text-[10px] bg-slate-300/80 text-slate-900 px-1.5 py-0.5 rounded font-mono font-bold">Pass: password</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Bottom note -->
    <p class="text-center text-xs text-slate-400 mt-6">
        Jika Anda lupa kata sandi atau akun dinonaktifkan, silakan hubungi bagian Kepegawaian & Tata Usaha Kelurahan.
    </p>
</div>

<script>
    function fillLogin(nip, password) {
        document.getElementById('login').value = nip;
        document.getElementById('password').value = password;
    }
</script>
@endsection
