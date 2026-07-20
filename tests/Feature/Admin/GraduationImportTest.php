<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Graduation;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

class GraduationImportTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function makeCsv(array $rows, array $headers = ['nama_siswa','nomor_peserta','program_keahlian','status_kelulusan']): UploadedFile
    {
        $lines = [implode(',', $headers)];
        foreach ($rows as $row) {
            $lines[] = implode(',', $row);
        }
        $content = implode("\n", $lines);
        $path    = tempnam(sys_get_temp_dir(), 'csv_') . '.csv';
        file_put_contents($path, $content);
        return new UploadedFile($path, 'import.csv', 'text/csv', null, true);
    }

    public function test_graduation_index_accessible(): void
    {
        $response = $this->actingAs($this->admin())->get('/admin/graduations');
        $response->assertStatus(200);
        $response->assertSee('Kelulusan');
    }

    public function test_import_valid_csv_stores_all_rows(): void
    {
        $file = $this->makeCsv([
            ['Budi Santoso', '001', 'TKJ', 'LULUS'],
            ['Siti Aminah', '002', 'AKL', 'LULUS'],
        ]);

        $response = $this->actingAs($this->admin())->post('/admin/graduations/import', [
            'csv_file'      => $file,
            'academic_year' => '2024/2025',
        ]);

        $response->assertRedirect(route('admin.graduations.index'));
        $this->assertDatabaseHas('graduations', ['exam_number' => '001', 'student_name' => 'Budi Santoso']);
        $this->assertDatabaseHas('graduations', ['exam_number' => '002', 'student_name' => 'Siti Aminah']);
    }

    public function test_import_csv_with_wrong_headers_is_rejected(): void
    {
        $file = $this->makeCsv([
            ['Budi', 'TKJ', 'LULUS'],
        ], ['nama', 'jurusan', 'hasil']); // missing required columns

        $response = $this->actingAs($this->admin())->post('/admin/graduations/import', [
            'csv_file'      => $file,
            'academic_year' => '2024/2025',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseCount('graduations', 0);
    }

    public function test_partial_import_skips_invalid_rows(): void
    {
        $file = $this->makeCsv([
            ['Budi Santoso', '001', 'TKJ', 'LULUS'],
            ['', '002', 'AKL', 'LULUS'],         // invalid: empty name
        ]);

        $response = $this->actingAs($this->admin())->post('/admin/graduations/import', [
            'csv_file'      => $file,
            'academic_year' => '2024/2025',
        ]);

        $response->assertRedirect(route('admin.graduations.index'));
        $this->assertDatabaseCount('graduations', 1);
        $response->assertSessionHas('success');
    }

    public function test_export_graduation_csv_downloads(): void
    {
        Graduation::factory()->create(['academic_year' => '2024/2025']);

        $response = $this->actingAs($this->admin())->get('/admin/graduations/export?academic_year=2024/2025');
        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('attachment', $response->headers->get('Content-Disposition'));
    }
}
