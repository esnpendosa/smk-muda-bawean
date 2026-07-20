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
        try {
            return Cache::remember($key, $ttl, $callback);
        } catch (\Throwable) {
            // Cache entry is corrupt (e.g., stale serialized objects after migration)
            Cache::forget($key);
            return $callback();
        }
    }

    /**
     * Stale-while-revalidate: serve stale if exists, regenerate in background after response.
     */
    public function rememberStale(string $key, int $ttl, callable $callback): mixed
    {
        $staleKey    = "stale_{$key}";
        $gracePeriod = 300;

        // If fresh cache exists, return it (guard against corrupt deserialization)
        try {
            if (($value = Cache::get($key)) !== null) {
                return $value;
            }
        } catch (\Throwable) {
            Cache::forget($key);
        }

        // If fresh is expired but stale exists
        try {
            if (($staleValue = Cache::get($staleKey)) !== null) {
                // Regenerate in the background after sending response
                dispatch(function () use ($key, $staleKey, $ttl, $gracePeriod, $callback) {
                    $fresh = $callback();
                    Cache::put($key, $fresh, $ttl);
                    Cache::put($staleKey, $fresh, $ttl + $gracePeriod);
                })->afterResponse();

                return $staleValue;
            }
        } catch (\Throwable) {
            Cache::forget($staleKey);
        }

        // Both expired/corrupt/missing: execute synchronously
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
