<?php

namespace Tests\Feature\Property;

use App\Models\Post;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaginationPropertyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * P9: sum item di semua halaman = N, tidak ada duplikat
     */
    public function test_pagination_completeness(): void
    {
        $n = 23;
        $pageSize = 10;
        Post::factory()->count($n)->create([
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        $totalPages = (int) ceil($n / $pageSize);
        $allIds = [];

        for ($page = 1; $page <= $totalPages; $page++) {
            $response = $this->get("/berita?page={$page}");
            $response->assertStatus(200);
            $posts = $response->viewData('posts');
            foreach ($posts as $post) {
                $allIds[] = $post->id;
            }
        }

        $this->assertCount($n, $allIds, 'Total item di semua halaman tidak sama dengan N');
        $this->assertCount($n, array_unique($allIds), 'Ada item duplikat di halaman berbeda');
    }
}
