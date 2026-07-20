<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Announcement;
use Illuminate\Support\Facades\Cache;

class SitemapService
{
    private array $staticUrls = [
        ['url' => '/',                  'priority' => '1.0', 'changefreq' => 'daily'],
        ['url' => '/profil/sejarah',    'priority' => '0.8', 'changefreq' => 'monthly'],
        ['url' => '/profil/visi-misi',  'priority' => '0.8', 'changefreq' => 'monthly'],
        ['url' => '/profil/pendidik',   'priority' => '0.7', 'changefreq' => 'monthly'],
        ['url' => '/kelulusan',         'priority' => '0.7', 'changefreq' => 'yearly'],
        ['url' => '/alumni',            'priority' => '0.6', 'changefreq' => 'monthly'],
        ['url' => '/ppdb',              'priority' => '0.9', 'changefreq' => 'weekly'],
    ];

    public function generate(): string
    {
        return Cache::remember('sitemap_xml', 60, function () {
            $urls = $this->staticUrls;

            // Published Posts
            Post::published()->select('slug', 'updated_at')->get()
                ->each(fn($post) => $urls[] = [
                    'url'        => '/berita/' . $post->slug,
                    'priority'   => '0.6',
                    'changefreq' => 'weekly',
                    'lastmod'    => $post->updated_at->toAtomString(),
                ]);

            // Published Announcements
            Announcement::published()->select('slug', 'updated_at')->get()
                ->each(fn($ann) => $urls[] = [
                    'url'        => '/pengumuman/' . $ann->slug,
                    'priority'   => '0.5',
                    'changefreq' => 'monthly',
                    'lastmod'    => $ann->updated_at->toAtomString(),
                ]);

            return $this->buildXml($urls);
        });
    }

    private function buildXml(array $urls): string
    {
        $baseUrl = config('app.url');
        $xml     = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml    .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        foreach ($urls as $entry) {
            $xml .= "  <url>" . PHP_EOL;
            $xml .= "    <loc>" . htmlspecialchars($baseUrl . $entry['url']) . "</loc>" . PHP_EOL;
            if (!empty($entry['lastmod'])) {
                $xml .= "    <lastmod>" . $entry['lastmod'] . "</lastmod>" . PHP_EOL;
            }
            $xml .= "    <changefreq>" . $entry['changefreq'] . "</changefreq>" . PHP_EOL;
            $xml .= "    <priority>" . $entry['priority'] . "</priority>" . PHP_EOL;
            $xml .= "  </url>" . PHP_EOL;
        }

        $xml .= '</urlset>';
        return $xml;
    }
}
