<?php

namespace Database\Factories;

use App\Models\ArsipPdf;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ArsipPdf>
 */
class ArsipPdfFactory extends Factory
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
            'periode_bulan' => fake()->numberBetween(1, 12),
            'periode_tahun' => 2026,
            'jenis_laporan' => 'bulanan',
            'file_path' => 'arsip_laporan/dummy.pdf',
            'nomor_arsip' => 'ARSIP/'.date('Y').'/'.fake()->unique()->numerify('###'),
        ];
    }
}
