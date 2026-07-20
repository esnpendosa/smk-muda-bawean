<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    /**
     * Standard cache remember.
     */
    public function remember(string $key, int $ttl, callable $callback): mixed
    {
        return Cache::remember($key, $ttl, $callback);
    }

    /**
     * Stale-while-revalidate: serve stale if exists, regenerate in background after response.
     */
    public function rememberStale(string $key, int $ttl, callable $callback): mixed
    {
        $staleKey    = "stale_{$key}";
        $gracePeriod = 300;

        // If fresh cache exists, return it
        if (($value = Cache::get($key)) !== null) {
            return $value;
        }

        // If fresh is expired but stale exists
        if (($staleValue = Cache::get($staleKey)) !== null) {
            // Regenerate in the background after sending response
            dispatch(function () use ($key, $staleKey, $ttl, $gracePeriod, $callback) {
                $fresh = $callback();
                Cache::put($key, $fresh, $ttl);
                Cache::put($staleKey, $fresh, $ttl + $gracePeriod);
            })->afterResponse();

            return $staleValue;
        }

        // Both expired or not existing: execute synchronously
        try {
            $fresh = $callback();
            Cache::put($key, $fresh, $ttl);
            Cache::put($staleKey, $fresh, $ttl + $gracePeriod);
            return $fresh;
        } catch (\Exception $e) {
            // Fallback directly to callback in case of cache connection failure
            report($e);
            return $callback();
        }
    }

    /**
     * Clear a cache key and its stale version.
     */
    public function forget(string $key): void
    {
        Cache::forget($key);
        Cache::forget("stale_{$key}");
    }

    /**
     * Clear multiple keys.
     */
    public function forgetByKeys(array $keys): void
    {
        foreach ($keys as $key) {
            $this->forget($key);
        }
    }
}
