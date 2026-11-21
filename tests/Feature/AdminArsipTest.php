<?php

namespace Tests\Feature;

use App\Models\ArsipPdf;
use App\Models\KopLaporan;
use App\Models\LaporanKinerja;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminArsipTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        KopLaporan::factory()->create();
    }

    public function test_admin_can_view_arsip_list(): void
    {
        $admin = User::factory()->admin()->create();
        $staff = User::factory()->staff()->create();
        ArsipPdf::factory()->count(2)->create(['user_id' => $staff->id]);

        $response = $this->actingAs($admin)->get(route('admin.arsip.index'));

        $response->assertStatus(200);
        $response->assertSee('Buku Arsip Dokumen Laporan Kinerja');
    }

    public function test_admin_can_manually_generate_and_archive_report(): void
    {
        $admin = User::factory()->admin()->create();
        $staff = User::factory()->staff()->create();

        LaporanKinerja::factory()->create([
            'user_id' => $staff->id,
            'tanggal_laporan' => now()->format('Y-m-d'),
            'status_verifikasi' => 'disetujui',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.arsip.generate-manual'), [
            'user_id' => $staff->id,
            'bulan' => now()->month,
            'tahun' => now()->year,
            'hanya_disetujui' => 1,
        ]);

        $response->assertRedirect(route('admin.arsip.index'));
        $this->assertDatabaseHas('arsip_pdfs', [
            'user_id' => $staff->id,
            'periode_bulan' => now()->month,
            'periode_tahun' => now()->year,
        ]);
    }

    public function test_admin_can_preview_and_download_archived_file(): void
    {
        $admin = User::factory()->admin()->create();
        $staff = User::factory()->staff()->create();

        $dummyContent = '%PDF-1.4 dummy content';
        $filePath = 'arsip_laporan/test_archive.pdf';
        Storage::disk('public')->put($filePath, $dummyContent);

        $arsip = ArsipPdf::create([
            'user_id' => $staff->id,
            'periode_bulan' => 11,
            'periode_tahun' => 2026,
            'jenis_laporan' => 'bulanan',
            'file_path' => $filePath,
            'nomor_arsip' => 'ARSIP/2026/11/0001',
        ]);

        $previewResponse = $this->actingAs($admin)->get(route('admin.arsip.preview', $arsip));
        $previewResponse->assertStatus(200);
        $this->assertEquals('application/pdf', $previewResponse->headers->get('Content-Type'));

        $downloadResponse = $this->actingAs($admin)->get(route('admin.arsip.download', $arsip));
        $downloadResponse->assertStatus(200);
        $this->assertTrue(str_contains($downloadResponse->headers->get('Content-Disposition'), 'attachment'));
    }

    public function test_admin_can_delete_arsip(): void
    {
        $admin = User::factory()->admin()->create();
        $staff = User::factory()->staff()->create();

        $filePath = 'arsip_laporan/to_delete.pdf';
        Storage::disk('public')->put($filePath, 'pdf content');

        $arsip = ArsipPdf::create([
            'user_id' => $staff->id,
            'periode_bulan' => 11,
            'periode_tahun' => 2026,
            'jenis_laporan' => 'bulanan',
            'file_path' => $filePath,
            'nomor_arsip' => 'ARSIP/2026/11/0099',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.arsip.destroy', $arsip));

        $response->assertRedirect(route('admin.arsip.index'));
        $this->assertDatabaseMissing('arsip_pdfs', [
            'id' => $arsip->id,
        ]);
        Storage::disk('public')->assertMissing($filePath);
    }
}
