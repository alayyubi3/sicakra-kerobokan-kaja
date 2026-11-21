<?php

namespace Database\Factories;

use App\Models\KopLaporan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<KopLaporan>
 */
class KopLaporanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_pemerintah' => 'PEMERINTAH KABUPATEN BADUNG',
            'nama_kecamatan' => 'KECAMATAN KUTA UTARA',
            'nama_kelurahan' => 'KELURAHAN KEROBOKAN KAJA',
            'alamat_kantor' => 'Jl. Raya Kerobokan No. 100, Kerobokan Kaja, Kec. Kuta Utara, Kabupaten Badung, Bali 80361',
            'kontak_kantor' => 'Telp. (0361) 425123 | Email: kelurahankerobokankaja@badungkab.go.id',
            'logo_path' => null,
            'nama_lurah' => 'I Gusti Ngurah Agung, S.STP., M.Si.',
            'nip_lurah' => '19850412 201001 1 012',
            'jabatan_penandatangan' => 'Lurah Kerobokan Kaja',
            'teks_pembuka_laporan' => 'Rekapitulasi pelaksanaan tugas pokok, fungsi, dan kegiatan harian aparatur pegawai di lingkungan Kantor Kelurahan Kerobokan Kaja:',
            'teks_penutup_laporan' => 'Demikian laporan kinerja harian/bulanan ini dibuat dengan sebenar-benarnya untuk dipergunakan sebagai bahan evaluasi dan arsip kepegawaian.',
            'is_active' => true,
        ];
    }
}
