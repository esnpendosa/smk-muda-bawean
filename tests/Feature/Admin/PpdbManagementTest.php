<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\PpdbRegistration;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PpdbManagementTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_ppdb_list_returns_all_registrations(): void
    {
        PpdbRegistration::factory()->count(3)->create();
        $response = $this->actingAs($this->admin())->get('/admin/ppdb');
        $response->assertStatus(200);
        $response->assertSee('PPDB');
    }

    public function test_ppdb_filter_by_status_works(): void
    {
        PpdbRegistration::factory()->create(['status' => 'diterima']);
        PpdbRegistration::factory()->create(['status' => 'ditolak']);

        $response = $this->actingAs($this->admin())->get('/admin/ppdb?status=diterima');
        $response->assertStatus(200);
        $response->assertSee('diterima');
    }

    public function test_ppdb_status_update_succeeds(): void
    {
        $reg = PpdbRegistration::factory()->create(['status' => 'menunggu']);

        $response = $this->actingAs($this->admin())->put("/admin/ppdb/{$reg->id}", [
            'status' => 'diterima',
        ]);

        $response->assertRedirect(route('admin.ppdb.show', $reg->id));
        $this->assertDatabaseHas('ppdb_registrations', ['id' => $reg->id, 'status' => 'diterima']);
    }

    public function test_ppdb_export_csv_downloadable(): void
    {
        PpdbRegistration::factory()->count(2)->create();

        $response = $this->actingAs($this->admin())->get('/admin/ppdb/export');
        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('attachment', $response->headers->get('Content-Disposition'));
    }

    public function test_ppdb_unauthenticated_redirects(): void
    {
        $response = $this->get('/admin/ppdb');
        $response->assertRedirect(route('admin.login'));
    }
}
