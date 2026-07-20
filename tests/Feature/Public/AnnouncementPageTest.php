<?php

namespace Tests\Feature\Public;

use App\Models\Announcement;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class AnnouncementPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_announcements_index_returns_successful_response(): void
    {
        Announcement::factory()->count(17)->create([
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        $response = $this->get('/pengumuman');
        $response->assertStatus(200);
        $response->assertSee('Pengumuman');
        // Assert pagination renders
        $response->assertSee('Menampilkan');
    }

    public function test_announcement_show_returns_successful_response_for_published(): void
    {
        $ann = Announcement::factory()->create([
            'title' => 'Important School Meeting',
            'slug' => 'important-school-meeting',
            'status' => 'published',
            'published_at' => now()->subDay(),
            'content' => '<p>Please attend the meeting.</p>',
        ]);

        $response = $this->get('/pengumuman/important-school-meeting');
        $response->assertStatus(200);
        $response->assertSee('Important School Meeting');
        $response->assertSee('Article');
    }

    public function test_announcement_show_returns_404_for_unpublished(): void
    {
        Announcement::factory()->create([
            'slug' => 'draft-meeting',
            'status' => 'draft',
            'published_at' => null,
        ]);

        $response = $this->get('/pengumuman/draft-meeting');
        $response->assertStatus(404);
    }

    public function test_announcement_show_returns_410_for_soft_deleted(): void
    {
        $ann = Announcement::factory()->create([
            'slug' => 'deleted-meeting',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        $ann->delete(); // Soft delete

        $response = $this->get('/pengumuman/deleted-meeting');
        $response->assertStatus(410);
    }

    public function test_announcement_download_successful_with_valid_file(): void
    {
        Storage::fake('local');
        $filePath = 'attachments/meeting.pdf';
        Storage::disk('local')->put($filePath, 'PDF content');

        $ann = Announcement::factory()->create([
            'slug' => 'meeting-download',
            'status' => 'published',
            'published_at' => now()->subDay(),
            'attachment' => $filePath,
        ]);

        $response = $this->get('/pengumuman/meeting-download/download');
        $response->assertStatus(200);
        $response->assertHeader('Content-Disposition', 'attachment; filename=meeting.pdf');
    }

    public function test_announcement_download_returns_404_if_no_attachment(): void
    {
        $ann = Announcement::factory()->create([
            'slug' => 'meeting-no-download',
            'status' => 'published',
            'published_at' => now()->subDay(),
            'attachment' => null,
        ]);

        $response = $this->get('/pengumuman/meeting-no-download/download');
        $response->assertStatus(404);
    }
}
