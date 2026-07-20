<?php

namespace App\Observers;

use App\Models\Announcement;
use App\Services\CacheService;

class AnnouncementObserver
{
    protected CacheService $cache;

    public function __construct(CacheService $cache)
    {
        $this->cache = $cache;
    }

    public function saved(Announcement $ann): void
    {
        $this->cache->forget('home_page');
        $this->cache->forget("announcement_{$ann->slug}");
        $this->cache->forget('sitemap_xml');
    }

    public function deleted(Announcement $ann): void
    {
        $this->cache->forget('home_page');
        $this->cache->forget("announcement_{$ann->slug}");
        $this->cache->forget('sitemap_xml');
    }
}
