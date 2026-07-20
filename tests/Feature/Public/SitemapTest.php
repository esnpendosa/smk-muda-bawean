<?php

namespace Tests\Feature\Public;

use App\Models\Post;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

class SitemapTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_returns_200_with_xml_content_type(): void
    {
        $response = $this->get('/sitemap.xml');
        $response->assertStatus(200);
        $this->assertStringContainsString('application/xml', $response->headers->get('Content-Type'));
    }

    public function test_sitemap_output_is_valid_xml(): void
    {
        $response = $this->get('/sitemap.xml');
        $xml = simplexml_load_string($response->getContent());
        $this->assertNotFalse($xml, 'Sitemap XML should be parseable');
        $this->assertEquals('urlset', $xml->getName());
    }

    public function test_sitemap_includes_static_urls(): void
    {
        $response = $this->get('/sitemap.xml');
        $response->assertSee('<loc>', false);
        $response->assertSee('/ppdb', false);
        $response->assertSee('/alumni', false);
    }

    public function test_robots_txt_returns_200_with_plain_text(): void
    {
        $response = $this->get('/robots.txt');
        $response->assertStatus(200);
        $this->assertStringContainsString('text/plain', $response->headers->get('Content-Type'));
    }

    public function test_robots_txt_contains_user_agent(): void
    {
        $response = $this->get('/robots.txt');
        $response->assertSee('User-agent', false);
    }


}
