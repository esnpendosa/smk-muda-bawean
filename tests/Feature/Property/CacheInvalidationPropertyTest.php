<?php

namespace Tests\Feature\Property;

use App\Models\Post;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CacheInvalidationPropertyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * P10: setelah konten diupdate dan cache diinvalidasi (≤5 detik),
     *      request berikutnya mengembalikan konten terbaru
     */
    public function test_cache_invalidation_consistency(): void
    {
        $post = Post::factory()->create([
            'title' => 'Judul Lama',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        // Heat the cache
        $this->get('/')->assertSee('Judul Lama');

        // Update content
        $startTime = microtime(true);
        
        $post->update(['title' => 'Judul Baru']);
        
        $elapsed = microtime(true) - $startTime;
        $this->assertLessThan(5, $elapsed, 'Invalidasi cache melebihi 5 detik');

        // Next request must return the updated content
        $this->get('/')->assertSee('Judul Baru');
        $this->get('/')->assertDontSee('Judul Lama');
    }
}
