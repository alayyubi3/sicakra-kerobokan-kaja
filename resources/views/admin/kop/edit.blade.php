@extends('layouts.app')

@section('title', 'Pengaturan Kop Laporan & Tanda Tangan')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900">Pengaturan Kop Surat & Tanda Tangan Laporan</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Atur identitas resmi instansi kelurahan, logo, dan blok pejabat penandatangan dokumen PDF.</p>
        </div>
    </div>

    <!-- Main Content Layout (Grid 2 Kolom) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Kolom Kiri: Form Input -->
        <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
            <form method="POST" action="{{ route('admin.kop.update') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Bagian 1: Identitas Kelurahan -->
                <div>
                    <h2 class="text-xs font-bold uppercase tracking-wider text-emerald-800 bg-emerald-50 px-3 py-1.5 rounded-lg mb-4 flex items-center space-x-1.5">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <span>1. Identitas Satuan Kerja & Kop Surat</span>
                    </h2>

                    <div class="space-y-4">
                        <!-- Nama Pemerintah -->
                        <div>
                            <label for="nama_pemerintah" class="block text-xs font-semibold text-slate-700 uppercase mb-1">
                                Tingkat Pemerintahan Daerah <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="nama_pemerintah" name="nama_pemerintah" required
                                value="{{ old('nama_pemerintah', $kop->nama_pemerintah) }}"
                                placeholder="Contoh: PEMERINTAH KABUPATEN BADUNG"
                                class="w-full px-3 py-2 text-xs sm:text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>

                        <!-- Grid Kecamatan & Kelurahan -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="nama_kecamatan" class="block text-xs font-semibold text-slate-700 uppercase mb-1">
                                    Nama Kecamatan <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" id="nama_kecamatan" name="nama_kecamatan" required
                                    value="{{ old('nama_kecamatan', $kop->nama_kecamatan) }}"
                                    placeholder="Contoh: KECAMATAN KUTA UTARA"
                                    class="w-full px-3 py-2 text-xs sm:text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            </div>

                            <div>
                                <label for="nama_kelurahan" class="block text-xs font-semibold text-slate-700 uppercase mb-1">
                                    Nama Kelurahan <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" id="nama_kelurahan" name="nama_kelurahan" required
                                    value="{{ old('nama_kelurahan', $kop->nama_kelurahan) }}"
                                    placeholder="Contoh: KELURAHAN KEROBOKAN KAJA"
                                    class="w-full px-3 py-2 text-xs sm:text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                        </div>

                        <!-- Alamat Kantor -->
                        <div>
                            <label for="alamat_kantor" class="block text-xs font-semibold text-slate-700 uppercase mb-1">
                                Alamat Lengkap Kantor Kelurahan <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="alamat_kantor" name="alamat_kantor" required
                                value="{{ old('alamat_kantor', $kop->alamat_kantor) }}"
                                placeholder="Contoh: Jl. Raya Kerobokan No. 100, Kerobokan Kaja, Kec. Kuta Utara, Kabupaten Badung, Bali 80361"
                                class="w-full px-3 py-2 text-xs sm:text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>

                        <!-- Kontak Kantor -->
                        <div>
                            <label for="kontak_kantor" class="block text-xs font-semibold text-slate-700 uppercase mb-1">
                                Kontak & Website Kantor
                            </label>
                            <input type="text" id="kontak_kantor" name="kontak_kantor"
                                value="{{ old('kontak_kantor', $kop->kontak_kantor) }}"
                                placeholder="Contoh: Telp. (0361) 425123 | Email: kelurahankerobokankaja@badungkab.go.id"
                                class="w-full px-3 py-2 text-xs sm:text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>

                        <!-- Upload Logo Instansi -->
                        <div>
                            <label for="logo" class="block text-xs font-semibold text-slate-700 uppercase mb-1">
                                Logo Resmi Instansi (Lambang Pemda)
                            </label>
                            <div class="flex items-center space-x-4">
                                @if($kop->logo_path)
                                    <div class="w-14 h-14 p-1 rounded-lg border border-slate-200 bg-slate-50 flex items-center justify-center">
                                        <img src="{{ asset('storage/' . $kop->logo_path) }}" alt="Logo Saat Ini" class="max-h-full max-w-full object-contain">
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <input id="logo" name="logo" type="file" accept="image/*"
                                        class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition">
                                    <p class="text-[11px] text-slate-400 mt-1">Format PNG, JPG, atau SVG (maksimal 2 MB).</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bagian 2: Pejabat Penandatangan (Lurah) -->
                <div class="border-t border-slate-200 pt-5">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-emerald-800 bg-emerald-50 px-3 py-1.5 rounded-lg mb-4 flex items-center space-x-1.5">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <span>2. Pejabat Penandatangan Laporan (Lurah)</span>
                    </h2>

                    <div class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="nama_lurah" class="block text-xs font-semibold text-slate-700 uppercase mb-1">
                                    Nama Lengkap Lurah / Pimpinan <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" id="nama_lurah" name="nama_lurah" required
                                    value="{{ old('nama_lurah', $kop->nama_lurah) }}"
                                    placeholder="Contoh: I Gusti Ngurah Agung, S.STP., M.Si."
                                    class="w-full px-3 py-2 text-xs sm:text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            </div>

                            <div>
                                <label for="nip_lurah" class="block text-xs font-semibold text-slate-700 uppercase mb-1">
                                    NIP Lurah <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" id="nip_lurah" name="nip_lurah" required
                                    value="{{ old('nip_lurah', $kop->nip_lurah) }}"
                                    placeholder="Contoh: 19850412 201001 1 012"
                                    class="w-full px-3 py-2 text-xs sm:text-sm font-mono border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                        </div>

                        <div>
                            <label for="jabatan_penandatangan" class="block text-xs font-semibold text-slate-700 uppercase mb-1">
                                Jabatan Penandatangan <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="jabatan_penandatangan" name="jabatan_penandatangan" required
                                value="{{ old('jabatan_penandatangan', $kop->jabatan_penandatangan) }}"
                                placeholder="Contoh: Lurah Kerobokan Kaja"
                                class="w-full px-3 py-2 text-xs sm:text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                    </div>
                </div>

                <!-- Bagian 3: Teks Header & Footer Laporan -->
                <div class="border-t border-slate-200 pt-5">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-emerald-800 bg-emerald-50 px-3 py-1.5 rounded-lg mb-4 flex items-center space-x-1.5">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                        <span>3. Teks Pembuka & Penutup Surat Laporan</span>
                    </h2>

                    <div class="space-y-4">
                        <div>
                            <label for="teks_pembuka_laporan" class="block text-xs font-semibold text-slate-700 uppercase mb-1">
                                Teks Pembuka (Sebelum Tabel Kinerja)
                            </label>
                            <textarea id="teks_pembuka_laporan" name="teks_pembuka_laporan" rows="2"
                                class="w-full px-3 py-2 text-xs sm:text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">{{ old('teks_pembuka_laporan', $kop->teks_pembuka_laporan) }}</textarea>
                        </div>

                        <div>
                            <label for="teks_penutup_laporan" class="block text-xs font-semibold text-slate-700 uppercase mb-1">
                                Teks Penutup (Sebelum Tanda Tangan)
                            </label>
                            <textarea id="teks_penutup_laporan" name="teks_penutup_laporan" rows="2"
                                class="w-full px-3 py-2 text-xs sm:text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">{{ old('teks_penutup_laporan', $kop->teks_penutup_laporan) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="border-t border-slate-200 pt-5 flex items-center justify-end">
                    <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold shadow-md transition">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Perubahan Template Kop
                    </button>
                </div>
            </form>
        </div>

        <!-- Kolom Kanan: Pratinjau Visual Template Kop & Tanda Tangan -->
        <div class="lg:col-span-5 space-y-4">
            <div class="sticky top-20 bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-200 mb-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-700">Simulasi Tampilan Kop & TTD</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">Preview A4</span>
                </div>

                <!-- Box Preview Kertas -->
                <div class="bg-slate-50 border border-slate-300 rounded-xl p-4 text-[11px] leading-tight text-slate-800 shadow-inner">

                    <!-- Kop Surat Preview -->
                    <div class="border-b-2 border-double border-slate-900 pb-2 mb-3">
                        <div class="flex items-center">
                            <div class="w-12 text-center flex-shrink-0">
                                @if($kop->logo_path)
                                    <img src="{{ asset('storage/' . $kop->logo_path) }}" alt="Logo" class="max-h-10 max-w-10 mx-auto">
                                @else
                                    <div class="w-9 h-9 mx-auto border border-emerald-700 rounded flex items-center justify-center font-bold text-emerald-800 text-xs">
                                        RI
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 text-center pr-4">
                                <div class="font-bold text-[9px] uppercase">{{ $kop->nama_pemerintah }}</div>
                                <div class="font-bold text-[10px] uppercase">{{ $kop->nama_kecamatan }}</div>
                                <div class="font-bold text-[12px] uppercase text-emerald-800">{{ $kop->nama_kelurahan }}</div>
                                <div class="text-[8px] text-slate-600">{{ $kop->alamat_kantor }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Judul Mini -->
                    <div class="text-center my-3">
                        <div class="font-bold underline text-[10px] uppercase">LAPORAN KINERJA PEGAWAI</div>
                        <div class="text-[8px] text-slate-400">Periode: [Bulan Terpilih]</div>
                    </div>

                    <!-- Garis Tabel Ilustrasi -->
                    <div class="border border-slate-300 rounded my-3 p-2 bg-white text-[9px] text-center text-slate-400">
                        [ Tabel Catatan Kinerja Harian Pegawai ]
                    </div>

                    <!-- Tanda Tangan Preview -->
                    <div class="grid grid-cols-2 text-[9px] text-center pt-2">
                        <div>
                            <div>Mengetahui,</div>
                            <div class="font-bold">{{ $kop->jabatan_penandatangan }}</div>
                            <div class="h-10"></div>
                            <div class="font-bold underline">{{ $kop->nama_lurah }}</div>
                            <div class="text-[8px] text-slate-500">NIP. {{ $kop->nip_lurah }}</div>
                        </div>
                        <div>
                            <div>{{ $kop && $kop->nama_kelurahan ? ucwords(strtolower(str_replace('KELURAHAN ', '', $kop->nama_kelurahan))) : 'Kerobokan Kaja' }}, {{ date('d/m/Y') }}</div>
                            <div class="font-bold">Pegawai Bersangkutan,</div>
                            <div class="h-10"></div>
                            <div class="font-bold underline">[Nama Pegawai]</div>
                            <div class="text-[8px] text-slate-500">NIP. [NIP Pegawai]</div>
                        </div>
                    </div>

                </div>

                <p class="text-[11px] text-slate-400 mt-3 text-center">
                    Perubahan data di formulir samping akan langsung diterapkan pada seluruh dokumen PDF yang dicetak di sistem SICAKRA.
                </p>
            </div>
        </div>

    </div>
</div>
@endsection
