<?php

namespace Tests\Feature;

use App\Models\KopLaporan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('SICAKRA');
    }

    public function test_login_screen_renders_kerobokan_kaja_identity(): void
    {
        KopLaporan::factory()->create([
            'nama_kelurahan' => 'KELURAHAN KEROBOKAN KAJA',
            'nama_kecamatan' => 'KECAMATAN KUTA UTARA',
            'nama_pemerintah' => 'PEMERINTAH KABUPATEN BADUNG',
        ]);

        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('KELURAHAN KEROBOKAN KAJA');
        $response->assertSee('KECAMATAN KUTA UTARA');
        $response->assertSee('PEMERINTAH KABUPATEN BADUNG');
    }

    public function test_staff_can_authenticate_using_nip(): void
    {
        $user = User::factory()->staff()->create([
            'nip' => '199203142019021001',
            'password' => bcrypt('password'),
            'status_aktif' => true,
        ]);

        $response = $this->post('/login', [
            'login' => '199203142019021001',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('staff.dashboard'));
    }

    public function test_admin_can_authenticate_using_email(): void
    {
        $admin = User::factory()->admin()->create([
            'email' => 'admin@sicakra.id',
            'password' => bcrypt('password'),
            'status_aktif' => true,
        ]);

        $response = $this->post('/login', [
            'login' => 'admin@sicakra.id',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($admin);
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_inactive_user_cannot_login(): void
    {
        $user = User::factory()->create([
            'nip' => '199000000000000001',
            'password' => bcrypt('password'),
            'status_aktif' => false,
        ]);

        $response = $this->post('/login', [
            'login' => '199000000000000001',
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('login');
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/login');
    }
}
