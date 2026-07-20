<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\SitemapService;
use App\Models\Setting;

class SitemapController extends Controller
{
    public function __construct(private SitemapService $sitemapService) {}

    /**
     * Return the sitemap XML.
     */
    public function index()
    {
        $xml = $this->sitemapService->generate();

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }

    /**
     * Return the robots.txt content.
     */
    public function robots()
    {
        $content = Setting::get('robots_txt',
            "User-agent: *\nAllow: /\nDisallow: /admin/\nSitemap: " . url('/sitemap.xml')
        );

        return response($content, 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }
}
