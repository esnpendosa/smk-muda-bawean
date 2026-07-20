<?php

namespace App\Observers;

use App\Models\Setting;
use App\Services\CacheService;

class SettingObserver
{
    protected CacheService $cache;

    public function __construct(CacheService $cache)
    {
        $this->cache = $cache;
    }

    public function saved(Setting $setting): void
    {
        $this->cache->forget('settings_all');
        $this->cache->forget('theme_colors');
    }

    public function deleted(Setting $setting): void
    {
        $this->cache->forget('settings_all');
        $this->cache->forget('theme_colors');
    }
}
