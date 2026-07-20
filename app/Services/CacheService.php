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
            $value = Cache::get($key);
            if ($value !== null && !$this->hasIncompleteClass($value)) {
                return $value;
            }
            if ($value !== null) {
                Cache::forget($key);
            }
        } catch (\Throwable) {
            Cache::forget($key);
        }

        $fresh = $callback();
        try {
            Cache::put($key, $fresh, $ttl);
        } catch (\Throwable $e) {
            report($e);
        }
        return $fresh;
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
            $value = Cache::get($key);
            if ($value !== null && !$this->hasIncompleteClass($value)) {
                return $value;
            }
            if ($value !== null) {
                Cache::forget($key);
            }
        } catch (\Throwable) {
            Cache::forget($key);
        }

        // If fresh is expired but stale exists
        try {
            $staleValue = Cache::get($staleKey);
            if ($staleValue !== null && !$this->hasIncompleteClass($staleValue)) {
                // Regenerate in the background after sending response
                dispatch(function () use ($key, $staleKey, $ttl, $gracePeriod, $callback) {
                    $fresh = $callback();
                    Cache::put($key, $fresh, $ttl);
                    Cache::put($staleKey, $fresh, $ttl + $gracePeriod);
                })->afterResponse();

                return $staleValue;
            }
            if ($staleValue !== null) {
                Cache::forget($staleKey);
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
     * Check recursively if the value contains __PHP_Incomplete_Class.
     */
    protected function hasIncompleteClass(mixed $value): bool
    {
        if ($value instanceof \__PHP_Incomplete_Class) {
            return true;
        }

        if (is_array($value)) {
            foreach ($value as $item) {
                if ($this->hasIncompleteClass($item)) {
                    return true;
                }
            }
        } elseif (is_object($value)) {
            foreach ((array) $value as $item) {
                if ($this->hasIncompleteClass($item)) {
                    return true;
                }
            }
        }

        return false;
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
