<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Post;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PostCrudTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_post_index_accessible_when_authenticated(): void
    {
        $response = $this->actingAs($this->admin())->get('/admin/posts');
        $response->assertStatus(200);
        $response->assertSee('Manajemen Berita');
    }

    public function test_post_create_form_accessible(): void
    {
        $response = $this->actingAs($this->admin())->get('/admin/posts/create');
        $response->assertStatus(200);
        $response->assertSee('Tambah Berita');
    }

    public function test_post_store_creates_record_with_auto_slug(): void
    {
        $admin = $this->admin();
        $response = $this->actingAs($admin)->post('/admin/posts', [
            'title'   => 'Test Post Title',
            'content' => '<p>Some content here</p>',
            'status'  => 'draft',
        ]);
        $response->assertRedirect(route('admin.posts.index'));
        $this->assertDatabaseHas('posts', ['title' => 'Test Post Title', 'slug' => 'test-post-title']);
    }

    public function test_post_store_rejects_invalid_thumbnail_type(): void
    {
        Storage::fake('private');
        $admin = $this->admin();
        $file  = UploadedFile::fake()->create('malware.exe', 100);

        $response = $this->actingAs($admin)->post('/admin/posts', [
            'title'     => 'Post With Bad File',
            'content'   => '<p>content</p>',
            'status'    => 'draft',
            'thumbnail' => $file,
        ]);
        $response->assertSessionHasErrors(['thumbnail']);
    }

    public function test_post_update_preserves_slug_when_title_unchanged(): void
    {
        $admin = $this->admin();
        $post  = Post::factory()->create(['slug' => 'original-slug', 'author_id' => $admin->id]);

        $this->actingAs($admin)->put("/admin/posts/{$post->id}", [
            'title'   => $post->title,
            'content' => '<p>updated</p>',
            'status'  => 'published',
        ]);

        $this->assertDatabaseHas('posts', ['id' => $post->id, 'slug' => 'original-slug']);
    }

    public function test_post_soft_delete(): void
    {
        $admin = $this->admin();
        $post  = Post::factory()->create(['author_id' => $admin->id]);

        $this->actingAs($admin)->delete("/admin/posts/{$post->id}");

        $this->assertSoftDeleted('posts', ['id' => $post->id]);
    }

    public function test_post_content_is_sanitized(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin)->post('/admin/posts', [
            'title'   => 'Sanitize Test',
            'content' => '<p>Safe</p><script>alert(1)</script>',
            'status'  => 'draft',
        ]);

        $post = Post::where('title', 'Sanitize Test')->first();
        $this->assertNotNull($post);
        $this->assertStringNotContainsString('<script>', $post->content);
        $this->assertStringContainsString('Safe', $post->content);
    }
}
