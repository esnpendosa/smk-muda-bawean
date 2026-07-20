<?php

namespace App\Observers;

use App\Models\Post;
use App\Services\CacheService;

class PostObserver
{
    protected CacheService $cache;

    public function __construct(CacheService $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Handle the Post "saved" event (created or updated).
     */
    public function saved(Post $post): void
    {
        $this->cache->forget('home_page');
        $this->cache->forget("post_{$post->slug}");
        $this->cache->forget('sitemap_xml');
    }

    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
        $this->cache->forget('home_page');
        $this->cache->forget("post_{$post->slug}");
        $this->cache->forget('sitemap_xml');
    }

    /**
     * Handle the Post "restored" event.
     */
    public function restored(Post $post): void
    {
        $this->cache->forget('home_page');
        $this->cache->forget("post_{$post->slug}");
        $this->cache->forget('sitemap_xml');
    }

    /**
     * Handle the Post "force deleted" event.
     */
    public function forceDeleted(Post $post): void
    {
        $this->cache->forget('home_page');
        $this->cache->forget("post_{$post->slug}");
        $this->cache->forget('sitemap_xml');
    }
}
