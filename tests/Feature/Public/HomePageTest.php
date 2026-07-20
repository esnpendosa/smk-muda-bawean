<?php

namespace Tests\Feature\Public;

use App\Models\Post;
use App\Models\Announcement;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_returns_successful_response(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_home_page_displays_maximum_six_posts(): void
    {
        Post::factory()->count(8)->create([
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        $response = $this->get('/');
        $posts = $response->viewData('posts');

        $this->assertCount(6, $posts);
    }

    public function test_home_page_displays_maximum_five_announcements(): void
    {
        Announcement::factory()->count(7)->create([
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        $response = $this->get('/');
        $announcements = $response->viewData('announcements');

        $this->assertCount(5, $announcements);
    }

    public function test_home_page_displays_empty_states(): void
    {
        $response = $this->get('/');
        $response->assertSee('Belum ada berita yang dipublikasikan.');
        $response->assertSee('Belum ada pengumuman yang dipublikasikan.');
    }

    public function test_home_page_uses_cache(): void
    {
        Post::factory()->count(2)->create([
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        // First request to heat cache
        $this->get('/');

        // Clear DB
        Post::query()->delete();

        // Second request should still see the cached posts
        $response = $this->get('/');
        $posts = $response->viewData('posts');
        
        $this->assertCount(2, $posts);
    }
}
