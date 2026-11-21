<?php

namespace Tests\Feature;

use App\Models\KopLaporan;
use App\Models\LaporanKinerja;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StaffCetakTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        KopLaporan::factory()->create();
    }

    public function test_staff_can_view_cetak_page(): void
    {
        $staff = User::factory()->staff()->create();
        LaporanKinerja::factory()->count(2)->create([
            'user_id' => $staff->id,
            'tanggal_laporan' => now()->format('Y-m-d'),
        ]);

        $response = $this->actingAs($staff)->get(route('staff.cetak.index'));

        $response->assertStatus(200);
        $response->assertSee('Cetak & Unduh Laporan Kinerja', false);
        $response->assertSee('KELURAHAN KEROBOKAN KAJA');
        $response->assertSee('PEMERINTAH KABUPATEN BADUNG');
    }

    public function test_staff_can_preview_pdf(): void
    {
        $staff = User::factory()->staff()->create();
        LaporanKinerja::factory()->create([
            'user_id' => $staff->id,
            'tanggal_laporan' => now()->format('Y-m-d'),
            'uraian_kegiatan' => 'Kegiatan pengujian cetak laporan',
        ]);

        $response = $this->actingAs($staff)->get(route('staff.cetak.preview', [
            'mode' => 'bulanan',
            'bulan' => now()->month,
            'tahun' => now()->year,
        ]));

        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
    }

    public function test_staff_download_pdf_automatically_archives_file(): void
    {
        $staff = User::factory()->staff()->create();
        LaporanKinerja::factory()->create([
            'user_id' => $staff->id,
            'tanggal_laporan' => now()->format('Y-m-d'),
            'uraian_kegiatan' => 'Pelaksanaan tugas harian untuk pengujian arsip otomatis',
        ]);

        $response = $this->actingAs($staff)->get(route('staff.cetak.download', [
            'mode' => 'bulanan',
            'bulan' => now()->month,
            'tahun' => now()->year,
        ]));

        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));

        // Pastikan tabel arsip_pdfs memiliki record baru
        $this->assertDatabaseHas('arsip_pdfs', [
            'user_id' => $staff->id,
            'periode_bulan' => now()->month,
            'periode_tahun' => now()->year,
        ]);
    }
}
