<?php

namespace Tests\Feature\Public;

use App\Models\Post;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NewsPageTest extends TestCase
{
    use RefreshDatabase;

    protected User $author;

    protected function setUp(): void
    {
        parent::setUp();
        $this->author = User::factory()->create([
            'name' => 'John Doe'
        ]);
    }

    public function test_news_index_returns_successful_response(): void
    {
        Post::factory()->count(12)->create([
            'status' => 'published',
            'published_at' => now()->subDay(),
            'author_id' => $this->author->id
        ]);

        $response = $this->get('/berita');
        $response->assertStatus(200);
        $response->assertSee('Kabar & Berita');
        // Assert pagination renders
        $response->assertSee('Menampilkan');
    }

    public function test_news_show_returns_successful_response_for_published_post(): void
    {
        $post = Post::factory()->create([
            'title' => 'Test News Post Title',
            'slug' => 'test-news-post-title',
            'status' => 'published',
            'published_at' => now()->subDay(),
            'author_id' => $this->author->id
        ]);

        $response = $this->get('/berita/test-news-post-title');
        $response->assertStatus(200);
        $response->assertSee('Test News Post Title');
        $response->assertSee('NewsArticle');
        $response->assertSee('John Doe');
    }

    public function test_news_show_returns_404_for_unpublished_post(): void
    {
        $post = Post::factory()->create([
            'slug' => 'test-draft-post',
            'status' => 'draft',
            'published_at' => null,
            'author_id' => $this->author->id
        ]);

        $response = $this->get('/berita/test-draft-post');
        $response->assertStatus(404);
    }

    public function test_news_show_returns_410_for_soft_deleted_post(): void
    {
        $post = Post::factory()->create([
            'slug' => 'test-deleted-post',
            'status' => 'published',
            'published_at' => now()->subDay(),
            'author_id' => $this->author->id
        ]);

        $post->delete(); // Soft delete

        $response = $this->get('/berita/test-deleted-post');
        $response->assertStatus(410);
    }
}
