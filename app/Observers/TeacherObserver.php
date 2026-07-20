<?php

namespace App\Observers;

use App\Models\Teacher;
use App\Services\CacheService;

class TeacherObserver
{
    protected CacheService $cache;

    public function __construct(CacheService $cache)
    {
        $this->cache = $cache;
    }

    public function saved(Teacher $teacher): void
    {
        $this->cache->forget('profil_pendidik');
    }

    public function deleted(Teacher $teacher): void
    {
        $this->cache->forget('profil_pendidik');
    }
}
