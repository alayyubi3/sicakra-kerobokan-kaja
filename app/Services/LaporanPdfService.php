<?php

namespace App\Services;

use App\Models\ArsipPdf;
use App\Models\KopLaporan;
use App\Models\LaporanKinerja;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LaporanPdfService
{
    /**
     * Nama-nama bulan dalam bahasa Indonesia.
     *
     * @var array<int, string>
     */
    public const BULAN_INDO = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    /**
     * Render PDF view instance.
     *
     * @param  Collection<int, LaporanKinerja>  $laporanList
     */
    public function renderPdf(
        User $user,
        Collection $laporanList,
        ?KopLaporan $kop = null,
        ?int $bulan = null,
        ?int $tahun = null,
        ?string $startDate = null,
        ?string $endDate = null,
        string $jenis = 'bulanan'
    ) {
        $kop = $kop ?: KopLaporan::getActive();
        $tahun = $tahun ?: (int) date('Y');
        $bulan = $bulan ?: (int) date('n');

        $namaBulan = self::BULAN_INDO[$bulan] ?? '-';

        // Convert logo to base64 if available to ensure 100% reliable rendering in dompdf
        $logoBase64 = null;
        if ($kop && $kop->logo_path && Storage::disk('public')->exists($kop->logo_path)) {
            $logoContent = Storage::disk('public')->get($kop->logo_path);
            $mimeType = Storage::disk('public')->mimeType($kop->logo_path) ?: 'image/png';
            $logoBase64 = 'data:'.$mimeType.';base64,'.base64_encode($logoContent);
        }

        $data = [
            'pegawai' => $user,
            'laporanList' => $laporanList,
            'kop' => $kop,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'namaBulan' => $namaBulan,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'jenis' => $jenis,
            'logoBase64' => $logoBase64,
            'tanggalCetak' => now()->translatedFormat('d F Y'),
        ];

        return Pdf::loadView('pdf.laporan-kinerja', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);
    }

    /**
     * Simpan PDF otomatis ke direktori server storage/app/public/arsip_laporan/ dan catat ke arsip_pdfs
     *
     * @param  Collection<int, LaporanKinerja>  $laporanList
     */
    public function archivePdf(
        User $user,
        Collection $laporanList,
        ?KopLaporan $kop = null,
        ?int $bulan = null,
        ?int $tahun = null,
        string $jenis = 'bulanan'
    ): ArsipPdf {
        $bulan = $bulan ?: (int) date('n');
        $tahun = $tahun ?: (int) date('Y');
        $bulanPad = str_pad((string) $bulan, 2, '0', STR_PAD_LEFT);

        $pdf = $this->renderPdf($user, $laporanList, $kop, $bulan, $tahun, null, null, $jenis);
        $pdfOutput = $pdf->output();

        // Ensure directory exists
        Storage::disk('public')->makeDirectory('arsip_laporan');

        $safeNip = preg_replace('/[^0-9]/', '', $user->nip ?: $user->id);
        $filename = "LAPORAN_{$safeNip}_{$tahun}_{$bulanPad}_".time().'_'.Str::lower(Str::random(5)).'.pdf';
        $relativePath = 'arsip_laporan/'.$filename;

        Storage::disk('public')->put($relativePath, $pdfOutput);

        // Generate nomor arsip unik
        $counter = ArsipPdf::where('periode_tahun', $tahun)->where('periode_bulan', $bulan)->count() + 1;
        $nomorArsip = sprintf('ARSIP/%s/%s/%04d', $tahun, $bulanPad, $counter);

        // Cek apakah nomor arsip sudah ada (fallback random jika konflik)
        while (ArsipPdf::where('nomor_arsip', $nomorArsip)->exists()) {
            $nomorArsip = sprintf('ARSIP/%s/%s/%04d-%s', $tahun, $bulanPad, ++$counter, Str::upper(Str::random(3)));
        }

        return ArsipPdf::create([
            'user_id' => $user->id,
            'periode_bulan' => $bulan,
            'periode_tahun' => $tahun,
            'jenis_laporan' => $jenis,
            'file_path' => $relativePath,
            'nomor_arsip' => $nomorArsip,
        ]);
    }
}
