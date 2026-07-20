<?php

namespace App\Observers;

use App\Models\Page;
use App\Services\CacheService;

class PageObserver
{
    protected CacheService $cache;

    public function __construct(CacheService $cache)
    {
        $this->cache = $cache;
    }

    public function saved(Page $page): void
    {
        $this->cache->forget("page_{$page->slug}");
        $this->cache->forget("profil_{$page->slug}");
    }

    public function deleted(Page $page): void
    {
        $this->cache->forget("page_{$page->slug}");
        $this->cache->forget("profil_{$page->slug}");
    }
}
