<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Beranda') - {{ config('app.name', 'SICAKRA - Kelurahan Kerobokan Kaja') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }
    </style>
    @stack('styles')
</head>
<body class="h-full flex flex-col antialiased text-slate-800 bg-slate-50">

    <!-- Top Navigation Bar -->
    <header class="bg-slate-900 border-b border-slate-800 text-white sticky top-0 z-50 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand / Logo -->
                <div class="flex items-center space-x-3">
                    <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('staff.dashboard') }}" class="flex items-center space-x-3 group">
                        <div class="w-10 h-10 rounded-lg bg-emerald-600 flex items-center justify-center font-bold text-white shadow-md shadow-emerald-900/40 group-hover:bg-emerald-500 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="font-bold text-lg tracking-wider text-emerald-400 leading-tight">SICAKRA</div>
                            <div class="text-xs text-slate-400 font-medium hidden sm:block">{{ $activeKop->nama_kelurahan ?? 'Kelurahan Kerobokan Kaja' }}</div>
                        </div>
                    </a>
                </div>

                <!-- Right profile & actions -->
                <div class="flex items-center space-x-4">
                    <div class="hidden md:flex flex-col text-right">
                        <span class="text-sm font-semibold text-white leading-tight">{{ auth()->user()->name }}</span>
                        <div class="flex items-center justify-end space-x-2 text-xs text-slate-400">
                            <span>NIP. {{ auth()->user()->nip }}</span>
                            <span>•</span>
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold {{ auth()->user()->role === 'admin' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' }}">
                                {{ auth()->user()->role === 'admin' ? 'ADMINISTRATOR' : 'STAFF PEGAWAI' }}
                            </span>
                        </div>
                    </div>

                    <!-- Role Badge Mobile -->
                    <span class="md:hidden inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold {{ auth()->user()->role === 'admin' ? 'bg-amber-500/20 text-amber-300' : 'bg-emerald-500/20 text-emerald-300' }}">
                        {{ auth()->user()->role === 'admin' ? 'Admin' : 'Staff' }}
                    </span>

                    <!-- Logout Button -->
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin keluar dari sistem?')" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-slate-300 hover:text-white hover:bg-slate-800 border border-slate-700 transition">
                            <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span class="hidden sm:inline">Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Role-Specific Sub-Navbar -->
        <nav class="bg-slate-800/90 backdrop-blur border-t border-slate-700/60 overflow-x-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex space-x-1 sm:space-x-4 py-2 min-w-max">
                    @if(auth()->user()->role === 'admin')
                        <!-- Admin Nav Links -->
                        <a href="{{ route('admin.dashboard') }}" class="px-3 py-1.5 rounded-md text-xs sm:text-sm font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}">
                            <span class="flex items-center space-x-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                <span>Dashboard</span>
                            </span>
                        </a>

                        <a href="{{ route('admin.verifikasi.index') }}" class="px-3 py-1.5 rounded-md text-xs sm:text-sm font-medium transition {{ request()->routeIs('admin.verifikasi.*') ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}">
                            <span class="flex items-center space-x-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                <span>Monitoring & Verifikasi</span>
                            </span>
                        </a>

                        <a href="{{ route('admin.pegawai.index') }}" class="px-3 py-1.5 rounded-md text-xs sm:text-sm font-medium transition {{ request()->routeIs('admin.pegawai.*') ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}">
                            <span class="flex items-center space-x-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                <span>Data Pegawai</span>
                            </span>
                        </a>

                        <a href="{{ route('admin.kop.edit') }}" class="px-3 py-1.5 rounded-md text-xs sm:text-sm font-medium transition {{ request()->routeIs('admin.kop.*') ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}">
                            <span class="flex items-center space-x-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                <span>Pengaturan Kop Surat</span>
                            </span>
                        </a>

                        <a href="{{ route('admin.arsip.index') }}" class="px-3 py-1.5 rounded-md text-xs sm:text-sm font-medium transition {{ request()->routeIs('admin.arsip.*') ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}">
                            <span class="flex items-center space-x-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                                <span>Arsip Laporan PDF</span>
                            </span>
                        </a>
                    @else
                        <!-- Staff Nav Links -->
                        <a href="{{ route('staff.dashboard') }}" class="px-3 py-1.5 rounded-md text-xs sm:text-sm font-medium transition {{ request()->routeIs('staff.dashboard') ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}">
                            <span class="flex items-center space-x-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                <span>Dashboard Saya</span>
                            </span>
                        </a>

                        <a href="{{ route('staff.laporan.create') }}" class="px-3 py-1.5 rounded-md text-xs sm:text-sm font-medium transition {{ request()->routeIs('staff.laporan.create') ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}">
                            <span class="flex items-center space-x-1.5">
                                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span class="font-semibold text-emerald-200">Input Kegiatan Baru</span>
                            </span>
                        </a>

                        <a href="{{ route('staff.laporan.index') }}" class="px-3 py-1.5 rounded-md text-xs sm:text-sm font-medium transition {{ request()->routeIs('staff.laporan.index') || request()->routeIs('staff.laporan.edit') || request()->routeIs('staff.laporan.show') ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}">
                            <span class="flex items-center space-x-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                <span>Daftar Laporan Kinerja</span>
                            </span>
                        </a>

                        <a href="{{ route('staff.cetak.index') }}" class="px-3 py-1.5 rounded-md text-xs sm:text-sm font-medium transition {{ request()->routeIs('staff.cetak.*') ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-700' }}">
                            <span class="flex items-center space-x-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                <span>Cetak & Download PDF</span>
                            </span>
                        </a>
                    @endif
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content Container -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Flash Alert Messages -->
        @if(session('success'))
            <div class="mb-5 rounded-lg bg-emerald-50 border border-emerald-200 p-4 shadow-sm flex items-start space-x-3">
                <svg class="w-5 h-5 text-emerald-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="text-sm font-medium text-emerald-800">{{ session('success') }}</div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-5 rounded-lg bg-rose-50 border border-rose-200 p-4 shadow-sm flex items-start space-x-3">
                <svg class="w-5 h-5 text-rose-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="text-sm font-medium text-rose-800">{{ session('error') }}</div>
            </div>
        @endif

        @if(session('info'))
            <div class="mb-5 rounded-lg bg-blue-50 border border-blue-200 p-4 shadow-sm flex items-start space-x-3">
                <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="text-sm font-medium text-blue-800">{{ session('info') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-5 rounded-lg bg-rose-50 border border-rose-200 p-4 shadow-sm">
                <div class="flex items-center space-x-2 text-rose-800 font-semibold text-sm mb-1">
                    <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <span>Terdapat beberapa kesalahan pengisian formulir:</span>
                </div>
                <ul class="list-disc list-inside text-xs text-rose-700 space-y-1 ml-6">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Page Content -->
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-4 mt-auto text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between">
            <div>
                <strong>{{ config('app.name', 'SICAKRA - Kelurahan Kerobokan Kaja') }}</strong> &copy; {{ date('Y') }} - Sistem Informasi Pelaporan Kinerja Pegawai.
            </div>
            <div class="mt-2 sm:mt-0 text-slate-400">
                {{ $activeKop->nama_pemerintah ?? 'PEMERINTAH KABUPATEN BADUNG' }} • {{ $activeKop->nama_kecamatan ?? 'KECAMATAN KUTA UTARA' }}
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
