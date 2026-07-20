<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Announcement;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AnnouncementCrudTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_announcement_index_accessible(): void
    {
        $response = $this->actingAs($this->admin())->get('/admin/announcements');
        $response->assertStatus(200);
        $response->assertSee('Pengumuman');
    }

    public function test_announcement_store_valid(): void
    {
        $admin = $this->admin();
        $response = $this->actingAs($admin)->post('/admin/announcements', [
            'title'  => 'New Announcement',
            'status' => 'published',
        ]);
        $response->assertRedirect(route('admin.announcements.index'));
        $this->assertDatabaseHas('announcements', ['title' => 'New Announcement']);
    }

    public function test_announcement_pdf_upload_valid(): void
    {
        Storage::fake('private');
        $admin = $this->admin();
        $pdf   = UploadedFile::fake()->create('attachment.pdf', 500, 'application/pdf');

        $response = $this->actingAs($admin)->post('/admin/announcements', [
            'title'      => 'With PDF',
            'status'     => 'draft',
            'attachment' => $pdf,
        ]);
        $response->assertRedirect(route('admin.announcements.index'));
        $ann = Announcement::where('title', 'With PDF')->first();
        $this->assertNotNull($ann->attachment);
    }

    public function test_announcement_non_pdf_attachment_rejected(): void
    {
        Storage::fake('private');
        $admin = $this->admin();
        $file  = UploadedFile::fake()->create('image.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($admin)->post('/admin/announcements', [
            'title'      => 'Bad Attach',
            'status'     => 'draft',
            'attachment' => $file,
        ]);
        $response->assertSessionHasErrors(['attachment']);
    }

    public function test_announcement_soft_delete(): void
    {
        $admin = $this->admin();
        $ann   = Announcement::factory()->create();
        $this->actingAs($admin)->delete("/admin/announcements/{$ann->id}");
        $this->assertSoftDeleted('announcements', ['id' => $ann->id]);
    }
}
