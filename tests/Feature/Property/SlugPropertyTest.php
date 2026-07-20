<?php

namespace Tests\Feature\Property;

use App\Models\Post;
use App\Services\SlugService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SlugPropertyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * P1: generate_slug(title) == generate_slug(generate_slug(title))
     * P2: slug hanya mengandung [a-z0-9\-] dan tidak dimulai/diakhiri '-'
     */
    public function test_slug_idempotence_and_valid_chars(): void
    {
        $service = new SlugService();
        $titles = [
            'Hello World', 'Berita Terbaru 2025', '  spaces  ',
            'Special!@#$%Characters', 'Judul dengan HURUF KAPITAL',
            'a', str_repeat('a', 255),
        ];

        // Random titles
        for ($i = 0; $i < 50; $i++) {
            $titles[] = $this->randomTitle();
        }

        foreach ($titles as $title) {
            if (strlen($title) < 1 || strlen($title) > 255) continue;

            $slug1 = $service->generate($title, Post::class);
            $slug2 = $service->generate($slug1, Post::class);

            // P1: idempotence
            $this->assertSame($slug1, $slug2, "Slug tidak idempoten untuk title: {$title}");

            // P2: karakter valid
            $this->assertMatchesRegularExpression(
                '/^[a-z0-9][a-z0-9\-]*[a-z0-9]$|^[a-z0-9]$/',
                $slug1,
                "Slug mengandung karakter invalid: {$slug1}"
            );
        }
    }

    private function randomTitle(): string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyz ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%';
        $len = random_int(1, 100);
        $title = '';
        for ($i = 0; $i < $len; $i++) {
            $title .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $title;
    }
}
