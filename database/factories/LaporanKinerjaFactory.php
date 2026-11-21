<?php

namespace Database\Factories;

use App\Models\LaporanKinerja;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LaporanKinerja>
 */
class LaporanKinerjaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'tanggal_laporan' => fake()->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'jam_mulai' => '08:00',
            'jam_selesai' => '11:30',
            'uraian_kegiatan' => fake()->sentence(8),
            'hasil_output' => fake()->words(3, true),
            'volume_kuantitas' => fake()->numberBetween(1, 10),
            'satuan' => fake()->randomElement(['Berkas', 'Kegiatan', 'Dokumen', 'Layanan']),
            'bukti_dukung_path' => null,
            'status_verifikasi' => fake()->randomElement(['terkirim', 'disetujui', 'revisi']),
            'catatan_admin' => null,
        ];
    }
}
