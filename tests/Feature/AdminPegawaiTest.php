<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminPegawaiTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_pegawai_index(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->staff()->count(3)->create();

        $response = $this->actingAs($admin)->get(route('admin.pegawai.index'));

        $response->assertStatus(200);
        $response->assertSee('Manajemen Data Pegawai');
    }

    public function test_admin_can_create_pegawai(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.pegawai.store'), [
            'name' => 'Budi Santoso, S.Kom.',
            'nip' => '199608122022031005',
            'email' => 'budi.santoso@kelurahan.go.id',
            'password' => 'password123',
            'jabatan' => 'Pranata Komputer',
            'bidang_seksi' => 'Pelayanan Umum',
            'role' => 'staff',
            'status_aktif' => 1,
        ]);

        $response->assertRedirect(route('admin.pegawai.index'));
        $this->assertDatabaseHas('users', [
            'nip' => '199608122022031005',
            'email' => 'budi.santoso@kelurahan.go.id',
            'role' => 'staff',
        ]);
    }

    public function test_admin_can_update_pegawai(): void
    {
        $admin = User::factory()->admin()->create();
        $staff = User::factory()->staff()->create([
            'name' => 'Nama Lama',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.pegawai.update', $staff), [
            'name' => 'Nama Baru Diperbarui',
            'nip' => $staff->nip,
            'email' => $staff->email,
            'jabatan' => 'Jabatan Baru',
            'bidang_seksi' => 'Pemerintahan',
            'role' => 'staff',
            'status_aktif' => 1,
        ]);

        $response->assertRedirect(route('admin.pegawai.index'));
        $staff->refresh();
        $this->assertEquals('Nama Baru Diperbarui', $staff->name);
    }

    public function test_admin_can_reset_password_pegawai(): void
    {
        $admin = User::factory()->admin()->create();
        $staff = User::factory()->staff()->create();

        $response = $this->actingAs($admin)->post(route('admin.pegawai.reset-password', $staff), [
            'new_password' => 'passwordBaru123',
        ]);

        $response->assertSessionHas('success');
        $staff->refresh();
        $this->assertTrue(Hash::check('passwordBaru123', $staff->password));
    }

    public function test_admin_can_toggle_pegawai_status(): void
    {
        $admin = User::factory()->admin()->create();
        $staff = User::factory()->staff()->create(['status_aktif' => true]);

        $response = $this->actingAs($admin)->post(route('admin.pegawai.toggle-status', $staff));

        $response->assertSessionHas('success');
        $staff->refresh();
        $this->assertFalse($staff->status_aktif);
    }

    public function test_staff_cannot_access_admin_pegawai(): void
    {
        $staff = User::factory()->staff()->create();

        $response = $this->actingAs($staff)->get(route('admin.pegawai.index'));

        $response->assertRedirect(route('staff.dashboard'));
    }
}
