<?php

namespace Tests\Feature\Property;

use App\Models\Post;
use App\Services\SchemaMarkupService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SchemaMarkupPropertyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * P6: output JSON-LD dapat di-parse dan round-trip konsisten
     */
    public function test_schema_markup_is_valid_json(): void
    {
        $service = new SchemaMarkupService();
        $posts = Post::factory()->count(10)->create();

        foreach ($posts as $post) {
            $schema = $service->newsArticle($post);
            if ($schema === null) continue;

            $json = json_encode($schema, JSON_UNESCAPED_UNICODE);
            $this->assertNotFalse($json, 'json_encode gagal');

            $decoded = json_decode($json, true);
            $this->assertNotNull($decoded, 'json_decode gagal');
            $this->assertEquals($schema, $decoded, 'Round-trip JSON tidak konsisten');
        }
    }
}
