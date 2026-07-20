<?php

namespace Tests\Feature\Public;

use App\Models\Graduation;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;

class GraduationPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        RateLimiter::clear('graduation_search:127.0.0.1');
    }

    public function test_graduation_index_returns_successful_response(): void
    {
        $response = $this->get('/kelulusan');
        $response->assertStatus(200);
        $response->assertSee('Verifikasi Kelulusan');
    }

    public function test_search_with_valid_nisn_returns_result(): void
    {
        Graduation::factory()->create([
            'exam_number' => '1234567890',
            'student_name' => 'Ahmad Fauzi',
            'status_kelulusan' => 'LULUS',
        ]);

        $response = $this->post('/kelulusan/search', [
            'nisn' => '1234567890',
        ]);

        $response->assertStatus(200);
        $response->assertSee('Ahmad Fauzi');
        $response->assertSee('LULUS');
        $response->assertSee('Unduh Surat Kelulusan');
    }

    public function test_search_with_invalid_nisn_redirects_back_with_error(): void
    {
        $response = $this->post('/kelulusan/search', [
            'nisn' => '1111111111',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('error', 'NISN tidak ditemukan');
    }

    public function test_search_validation_fails_for_non_numeric_or_short_nisn(): void
    {
        $response = $this->post('/kelulusan/search', [
            'nisn' => 'abc',
        ]);

        $response->assertSessionHasErrors(['nisn']);
    }

    public function test_search_rate_limiter_blocks_sixth_request(): void
    {
        Graduation::factory()->create([
            'exam_number' => '1234567890',
            'student_name' => 'Ahmad Fauzi',
            'status_kelulusan' => 'LULUS',
        ]);

        // Trigger 5 requests
        for ($i = 0; $i < 5; $i++) {
            $this->post('/kelulusan/search', ['nisn' => '1234567890']);
        }

        // 6th request should return 429
        $response = $this->post('/kelulusan/search', ['nisn' => '1234567890']);
        $response->assertStatus(429);
        $response->assertSee('Terlalu Banyak Percobaan');
    }

    public function test_download_pdf_success_for_lulus(): void
    {
        Graduation::factory()->create([
            'exam_number' => '1234567890',
            'status_kelulusan' => 'LULUS',
        ]);

        $response = $this->get('/kelulusan/1234567890/download');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
        $response->assertHeader('Content-Disposition', 'attachment; filename="sklk_1234567890.pdf"');
    }

    public function test_download_pdf_returns_403_for_not_lulus(): void
    {
        Graduation::factory()->create([
            'exam_number' => '1234567890',
            'status_kelulusan' => 'TIDAK LULUS',
        ]);

        $response = $this->get('/kelulusan/1234567890/download');
        $response->assertStatus(403);
    }
}
