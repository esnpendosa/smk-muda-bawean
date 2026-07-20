<?php

namespace Tests\Feature\Public;

use App\Models\Teacher;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfilePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_sejarah_returns_successful_response(): void
    {
        $response = $this->get('/profil/sejarah');
        $response->assertStatus(200);
        $response->assertSee('Sejarah Sekolah');
        $response->assertSee('EducationalOrganization');
    }

    public function test_visi_misi_returns_successful_response(): void
    {
        $response = $this->get('/profil/visi-misi');
        $response->assertStatus(200);
        $response->assertSee('Visi & Misi');
        $response->assertSee('EducationalOrganization');
    }

    public function test_pendidik_returns_successful_response(): void
    {
        Teacher::factory()->create([
            'name' => 'Budi Santoso',
            'position' => 'Guru IT',
            'photo' => null,
        ]);

        $response = $this->get('/profil/pendidik');
        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
        $response->assertSee('Guru IT');
    }

    public function test_invalid_profile_path_returns_404(): void
    {
        $response = $this->get('/profil/kontak');
        $response->assertStatus(404);
    }
}
