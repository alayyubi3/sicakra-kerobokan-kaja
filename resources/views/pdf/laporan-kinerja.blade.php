<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kinerja Pegawai - {{ $pegawai->name }}</title>
    <style>
        @page {
            margin: 1.5cm 1.5cm 1.5cm 1.5cm;
            size: a4 portrait;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10pt;
            color: #1e293b;
            line-height: 1.35;
            margin: 0;
            padding: 0;
        }

        /* Kop Surat Styling */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0px;
        }

        .kop-table td {
            vertical-align: middle;
        }

        .kop-logo {
            width: 80px;
            text-align: center;
        }

        .kop-logo img {
            max-width: 75px;
            max-height: 75px;
        }

        .kop-text {
            text-align: center;
            padding-right: 20px;
        }

        .kop-pemerintah {
            font-size: 11pt;
            font-weight: bold;
            letter-spacing: 0.5px;
            color: #0f172a;
            text-transform: uppercase;
        }

        .kop-kecamatan {
            font-size: 12pt;
            font-weight: bold;
            letter-spacing: 0.5px;
            color: #0f172a;
            text-transform: uppercase;
        }

        .kop-kelurahan {
            font-size: 14pt;
            font-weight: bold;
            letter-spacing: 1px;
            color: #047857;
            text-transform: uppercase;
            margin-top: 1px;
        }

        .kop-alamat {
            font-size: 8.5pt;
            color: #475569;
            margin-top: 2px;
        }

        .kop-kontak {
            font-size: 7.5pt;
            color: #64748b;
        }

        /* Garis Pembatas Ganda Kop Surat Resmi */
        .kop-line-double {
            border-top: 2.5px solid #000;
            border-bottom: 1px solid #000;
            height: 2px;
            margin-top: 5px;
            margin-bottom: 15px;
        }

        /* Judul Dokumen */
        .doc-title-container {
            text-align: center;
            margin-bottom: 15px;
        }

        .doc-title {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-decoration: underline;
            color: #0f172a;
        }

        .doc-subtitle {
            font-size: 9pt;
            color: #475569;
            margin-top: 3px;
        }

        /* Data Pegawai Info Box */
        .pegawai-box {
            width: 100%;
            margin-bottom: 12px;
            border-collapse: collapse;
            font-size: 9pt;
        }

        .pegawai-box td {
            padding: 2.5px 0;
            vertical-align: top;
        }

        .pegawai-label {
            width: 18%;
            color: #334155;
            font-weight: 500;
        }

        .pegawai-sep {
            width: 2%;
            text-align: center;
        }

        .pegawai-val {
            width: 80%;
            font-weight: bold;
            color: #0f172a;
        }

        .teks-pengantar {
            font-size: 8.5pt;
            color: #334155;
            margin-bottom: 10px;
            text-align: justify;
        }

        /* Tabel Kinerja */
        .kinerja-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
            margin-bottom: 15px;
        }

        .kinerja-table th {
            background-color: #f1f5f9;
            color: #0f172a;
            font-weight: bold;
            text-align: center;
            border: 1px solid #94a3b8;
            padding: 6px 4px;
        }

        .kinerja-table td {
            border: 1px solid #cbd5e1;
            padding: 5px 4px;
            vertical-align: top;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .badge-status {
            display: inline-block;
            font-size: 7.5pt;
            font-weight: bold;
            text-transform: uppercase;
            padding: 1px 4px;
            border-radius: 2px;
        }

        .badge-disetujui {
            background-color: #dcfce7;
            color: #15803d;
            border: 0.5px solid #86efac;
        }

        .badge-terkirim {
            background-color: #fef3c7;
            color: #b45309;
            border: 0.5px solid #fde68a;
        }

        .badge-revisi {
            background-color: #ffe4e6;
            color: #be123c;
            border: 0.5px solid #fecdd3;
        }

        /* Tanda Tangan */
        .ttd-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
            margin-top: 15px;
            page-break-inside: avoid;
        }

        .ttd-col {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }

        .ttd-space {
            height: 60px;
        }

        .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
            color: #0f172a;
        }

        .ttd-nip {
            font-size: 8.5pt;
            color: #334155;
            margin-top: 2px;
        }

        /* Footer halaman cetak */
        .pdf-footer {
            margin-top: 20px;
            font-size: 7pt;
            color: #94a3b8;
            border-top: 0.5px solid #e2e8f0;
            padding-top: 4px;
            text-align: right;
        }
    </style>
</head>
<body>

    <!-- KOP LAPORAN -->
    <table class="kop-table">
        <tr>
            <td class="kop-logo">
                @if(!empty($logoBase64))
                    <img src="{{ $logoBase64 }}" alt="Logo Instansi">
                @else
                    <!-- Emblem Garuda / Monogram Lambang Daerah Default -->
                    <div style="width: 65px; height: 65px; margin: 0 auto; border: 2px solid #047857; border-radius: 8px; text-align: center; line-height: 60px; font-weight: bold; color: #047857; font-size: 16pt;">
                        RI
                    </div>
                @endif
            </td>
            <td class="kop-text">
                <div class="kop-pemerintah">{{ $kop->nama_pemerintah ?? 'PEMERINTAH KABUPATEN BADUNG' }}</div>
                <div class="kop-kecamatan">{{ $kop->nama_kecamatan ?? 'KECAMATAN KUTA UTARA' }}</div>
                <div class="kop-kelurahan">{{ $kop->nama_kelurahan ?? 'KELURAHAN KEROBOKAN KAJA' }}</div>
                <div class="kop-alamat">{{ $kop->alamat_kantor ?? 'Jl. Raya Kerobokan No. 100, Kerobokan Kaja, Kec. Kuta Utara, Kabupaten Badung, Bali 80361' }}</div>
                @if(!empty($kop->kontak_kantor))
                    <div class="kop-kontak">{{ $kop->kontak_kantor }}</div>
                @endif
            </td>
        </tr>
    </table>

    <div class="kop-line-double"></div>

    <!-- JUDUL LAPORAN -->
    <div class="doc-title-container">
        <div class="doc-title">LAPORAN KINERJA DAN CAPAIAN TUGAS PEGAWAI</div>
        <div class="doc-subtitle">
            @if($jenis === 'bulanan')
                Periode: Bulan {{ $namaBulan }} Tahun {{ $tahun }}
            @elseif($startDate && $endDate)
                Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
            @else
                Periode: Tahun {{ $tahun }}
            @endif
        </div>
    </div>

    <!-- IDENTITAS PEGAWAI -->
    <table class="pegawai-box">
        <tr>
            <td class="pegawai-label">Nama Pegawai</td>
            <td class="pegawai-sep">:</td>
            <td class="pegawai-val">{{ $pegawai->name }}</td>
        </tr>
        <tr>
            <td class="pegawai-label">NIP</td>
            <td class="pegawai-sep">:</td>
            <td class="pegawai-val">{{ $pegawai->nip }}</td>
        </tr>
        <tr>
            <td class="pegawai-label">Jabatan</td>
            <td class="pegawai-sep">:</td>
            <td class="pegawai-val">{{ $pegawai->jabatan ?? '-' }}</td>
        </tr>
        <tr>
            <td class="pegawai-label">Bidang / Seksi</td>
            <td class="pegawai-sep">:</td>
            <td class="pegawai-val">{{ $pegawai->bidang_seksi ?? '-' }}</td>
        </tr>
        <tr>
            <td class="pegawai-label">Unit Kerja</td>
            <td class="pegawai-sep">:</td>
            <td class="pegawai-val">{{ $kop->nama_kelurahan ?? 'Kelurahan Kerobokan Kaja' }}</td>
        </tr>
    </table>

    @if(!empty($kop->teks_pembuka_laporan))
        <div class="teks-pengantar">{{ $kop->teks_pembuka_laporan }}</div>
    @endif

    <!-- TABEL REKAPITULASI KINERJA -->
    <table class="kinerja-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 14%;">Hari / Tgl</th>
                <th style="width: 11%;">Waktu</th>
                <th style="width: 38%;">Uraian Kegiatan Tugas</th>
                <th style="width: 20%;">Hasil / Output</th>
                <th style="width: 12%;">Volume</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporanList as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($item->tanggal_laporan)->translatedFormat('l') }},<br>
                        <strong>{{ \Carbon\Carbon::parse($item->tanggal_laporan)->format('d/m/Y') }}</strong>
                    </td>
                    <td class="text-center">
                        {{ substr($item->jam_mulai, 0, 5) }} - {{ substr($item->jam_selesai, 0, 5) }}
                    </td>
                    <td>
                        {{ $item->uraian_kegiatan }}
                    </td>
                    <td>
                        {{ $item->hasil_output }}
                    </td>
                    <td class="text-center">
                        <strong>{{ (float) $item->volume_kuantitas }}</strong> {{ $item->satuan }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 15px; color: #64748b;">
                        <em>Tidak ada data laporan kinerja pada periode yang dipilih.</em>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if(!empty($kop->teks_penutup_laporan))
        <div class="teks-pengantar">{{ $kop->teks_penutup_laporan }}</div>
    @endif

    <!-- BLOK TANDA TANGAN RESMI -->
    <table class="ttd-table">
        <tr>
            <!-- Mengetahui Pimpinan (Lurah) di sebelah Kiri -->
            <td class="ttd-col">
                <div>Mengetahui,</div>
                <div style="font-weight: bold;">{{ $kop->jabatan_penandatangan ?? 'Lurah Kerobokan Kaja' }}</div>
                <div class="ttd-space"></div>
                <div class="ttd-nama">{{ $kop->nama_lurah ?? 'I Gusti Ngurah Agung, S.STP., M.Si.' }}</div>
                <div class="ttd-nip">NIP. {{ $kop->nip_lurah ?? '19850412 201001 1 012' }}</div>
            </td>

            <!-- Pegawai yang Bersangkutan di sebelah Kanan -->
            <td class="ttd-col">
                <div>{{ $kop && $kop->nama_kelurahan ? ucwords(strtolower(str_replace('KELURAHAN ', '', $kop->nama_kelurahan))) : 'Kerobokan Kaja' }}, {{ $tanggalCetak }}</div>
                <div style="font-weight: bold;">Pegawai yang Bersangkutan,</div>
                <div class="ttd-space"></div>
                <div class="ttd-nama">{{ $pegawai->name }}</div>
                <div class="ttd-nip">NIP. {{ $pegawai->nip }}</div>
            </td>
        </tr>
    </table>

    <div class="pdf-footer">
        Dicetak secara digital melalui Sistem Informasi Pelaporan Kinerja Pegawai (SICAKRA) pada {{ now()->format('d/m/Y H:i:s') }}
    </div>

</body>
</html>
