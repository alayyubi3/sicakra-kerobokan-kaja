<?php

namespace Tests\Feature;

use App\Models\KopLaporan;
use App\Models\LaporanKinerja;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminVerifikasiTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_verifikasi_index(): void
    {
        $admin = User::factory()->admin()->create();
        $staff = User::factory()->staff()->create();
        LaporanKinerja::factory()->count(3)->create(['user_id' => $staff->id]);

        $response = $this->actingAs($admin)->get(route('admin.verifikasi.index'));

        $response->assertStatus(200);
        $response->assertSee('Monitoring & Verifikasi Kinerja Pegawai', false);
    }

    public function test_admin_can_approve_laporan(): void
    {
        $admin = User::factory()->admin()->create();
        $laporan = LaporanKinerja::factory()->create(['status_verifikasi' => 'terkirim']);

        $response = $this->actingAs($admin)->post(route('admin.verifikasi.update', $laporan), [
            'status_verifikasi' => 'disetujui',
            'catatan_admin' => 'Tugas telah selesai dengan baik dan sesuai target.',
        ]);

        $response->assertSessionHas('success');
        $laporan->refresh();
        $this->assertEquals('disetujui', $laporan->status_verifikasi);
        $this->assertEquals('Tugas telah selesai dengan baik dan sesuai target.', $laporan->catatan_admin);
    }

    public function test_admin_can_request_revision_with_required_notes(): void
    {
        $admin = User::factory()->admin()->create();
        $laporan = LaporanKinerja::factory()->create(['status_verifikasi' => 'terkirim']);

        // Jika revisi tanpa catatan, validasi harus gagal
        $failResponse = $this->actingAs($admin)->post(route('admin.verifikasi.update', $laporan), [
            'status_verifikasi' => 'revisi',
            'catatan_admin' => '',
        ]);
        $failResponse->assertSessionHasErrors('catatan_admin');

        // Dengan catatan, berhasil
        $successResponse = $this->actingAs($admin)->post(route('admin.verifikasi.update', $laporan), [
            'status_verifikasi' => 'revisi',
            'catatan_admin' => 'Mohon tambahkan nomor registrasi surat pada uraian hasil.',
        ]);
        $successResponse->assertSessionHas('success');
        $laporan->refresh();
        $this->assertEquals('revisi', $laporan->status_verifikasi);
    }

    public function test_admin_can_bulk_approve_laporans(): void
    {
        $admin = User::factory()->admin()->create();
        $laporans = LaporanKinerja::factory()->count(3)->create(['status_verifikasi' => 'terkirim']);

        $response = $this->actingAs($admin)->post(route('admin.verifikasi.bulk-approve'), [
            'selected_ids' => $laporans->pluck('id')->toArray(),
        ]);

        $response->assertSessionHas('success');
        foreach ($laporans as $lap) {
            $this->assertEquals('disetujui', $lap->fresh()->status_verifikasi);
        }
    }

    public function test_admin_can_update_kop_laporan(): void
    {
        $admin = User::factory()->admin()->create();
        $kop = KopLaporan::factory()->create();

        $response = $this->actingAs($admin)->put(route('admin.kop.update'), [
            'nama_pemerintah' => 'PEMERINTAH KOTA SURAKARTA',
            'nama_kecamatan' => 'KECAMATAN BANJARSARI',
            'nama_kelurahan' => 'KELURAHAN NUSUKAN',
            'alamat_kantor' => 'Jl. Kapten Piere Tendean No. 12',
            'kontak_kantor' => 'Telp: (0271) 712345',
            'nama_lurah' => 'Bambang Irawan, S.STP.',
            'nip_lurah' => '198001012005011003',
            'jabatan_penandatangan' => 'Lurah Nusukan',
            'teks_pembuka_laporan' => 'Rekapitulasi capaian kerja harian:',
            'teks_penutup_laporan' => 'Demikian laporan dibuat.',
        ]);

        $response->assertRedirect(route('admin.kop.edit'));
        $this->assertDatabaseHas('kop_laporans', [
            'nama_kelurahan' => 'KELURAHAN NUSUKAN',
            'nama_lurah' => 'Bambang Irawan, S.STP.',
        ]);
    }
}
