<?php

namespace Tests\Feature;

use App\Models\LaporanKinerja;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StaffLaporanTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_can_view_dashboard(): void
    {
        $staff = User::factory()->staff()->create();

        LaporanKinerja::factory()->count(3)->create([
            'user_id' => $staff->id,
            'tanggal_laporan' => now()->format('Y-m-d'),
            'status_verifikasi' => 'disetujui',
        ]);

        $response = $this->actingAs($staff)->get(route('staff.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Selamat Datang');
        $response->assertSee($staff->name);
    }

    public function test_staff_can_create_laporan_kinerja(): void
    {
        $staff = User::factory()->staff()->create();

        $response = $this->actingAs($staff)->post(route('staff.laporan.store'), [
            'tanggal_laporan' => now()->format('Y-m-d'),
            'jam_mulai' => '08:30',
            'jam_selesai' => '11:00',
            'uraian_kegiatan' => 'Melakukan pelayanan administrasi pembuatan surat pengantar KTP warga.',
            'hasil_output' => 'Surat Pengantar Diterbitkan',
            'volume_kuantitas' => 5,
            'satuan' => 'Berkas',
        ]);

        $response->assertRedirect(route('staff.laporan.index'));
        $this->assertDatabaseHas('laporan_kinerjas', [
            'user_id' => $staff->id,
            'hasil_output' => 'Surat Pengantar Diterbitkan',
            'status_verifikasi' => 'terkirim',
        ]);
    }

    public function test_staff_can_update_unapproved_laporan(): void
    {
        $staff = User::factory()->staff()->create();
        $laporan = LaporanKinerja::factory()->create([
            'user_id' => $staff->id,
            'status_verifikasi' => 'revisi',
            'catatan_admin' => 'Perbaiki uraian tugas',
        ]);

        $response = $this->actingAs($staff)->put(route('staff.laporan.update', $laporan), [
            'tanggal_laporan' => $laporan->tanggal_laporan->format('Y-m-d'),
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:00',
            'uraian_kegiatan' => 'Uraian yang sudah diperbaiki sesuai arahan pimpinan.',
            'hasil_output' => 'Output Selesai',
            'volume_kuantitas' => 2,
            'satuan' => 'Berkas',
        ]);

        $response->assertRedirect(route('staff.laporan.index'));
        $laporan->refresh();
        $this->assertEquals('terkirim', $laporan->status_verifikasi);
        $this->assertEquals('Uraian yang sudah diperbaiki sesuai arahan pimpinan.', $laporan->uraian_kegiatan);
    }

    public function test_staff_cannot_edit_approved_laporan(): void
    {
        $staff = User::factory()->staff()->create();
        $laporan = LaporanKinerja::factory()->create([
            'user_id' => $staff->id,
            'status_verifikasi' => 'disetujui',
        ]);

        $response = $this->actingAs($staff)->get(route('staff.laporan.edit', $laporan));
        $response->assertRedirect(route('staff.laporan.index'));

        $updateResponse = $this->actingAs($staff)->put(route('staff.laporan.update', $laporan), [
            'tanggal_laporan' => $laporan->tanggal_laporan->format('Y-m-d'),
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:00',
            'uraian_kegiatan' => 'Percobaan perubahan ilegal',
            'hasil_output' => 'Output',
            'volume_kuantitas' => 1,
            'satuan' => 'Berkas',
        ]);

        $updateResponse->assertRedirect(route('staff.laporan.index'));
        $laporan->refresh();
        $this->assertEquals('disetujui', $laporan->status_verifikasi);
    }

    public function test_staff_cannot_access_other_staff_laporan(): void
    {
        $staff1 = User::factory()->staff()->create();
        $staff2 = User::factory()->staff()->create();

        $laporan = LaporanKinerja::factory()->create([
            'user_id' => $staff2->id,
            'status_verifikasi' => 'terkirim',
        ]);

        $response = $this->actingAs($staff1)->get(route('staff.laporan.show', $laporan));
        $response->assertStatus(403);
    }

    public function test_staff_can_delete_unapproved_laporan(): void
    {
        $staff = User::factory()->staff()->create();
        $laporan = LaporanKinerja::factory()->create([
            'user_id' => $staff->id,
            'status_verifikasi' => 'terkirim',
        ]);

        $response = $this->actingAs($staff)->delete(route('staff.laporan.destroy', $laporan));

        $response->assertRedirect(route('staff.laporan.index'));
        $this->assertDatabaseMissing('laporan_kinerjas', [
            'id' => $laporan->id,
        ]);
    }
}
