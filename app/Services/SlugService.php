<?php

namespace App\Services;

use Illuminate\Support\Str;

class SlugService
{
    /**
     * Generate a unique slug for a given model.
     */
    public function generate(string $title, string $modelClass, ?int $excludeId = null): string
    {
        $slug = $this->slugify($title);
        $originalSlug = $slug;
        $counter = 1;

        while ($this->exists($slug, $modelClass, $excludeId)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Convert string to clean slug.
     */
    private function slugify(string $title): string
    {
        // Lowercase
        $slug = strtolower($title);
        // Replace non-alphanumeric with -
        $slug = preg_replace('/[^a-z0-9]+/i', '-', $slug);
        // Replace multiple dashes with single dash
        $slug = preg_replace('/-{2,}/', '-', $slug);
        // Trim dashes
        $slug = trim($slug, '-');

        if ($slug === '') {
            $slug = 'content';
        }

        return $slug;
    }

    /**
     * Check if slug exists in the model's database table.
     */
    private function exists(string $slug, string $modelClass, ?int $excludeId = null): bool
    {
        $query = $modelClass::where('slug', $slug);

        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId);
        }

        // Include trashed/soft deleted items in uniqueness check to prevent 404/410 clashes
        if (method_exists($modelClass, 'withTrashed')) {
            $query->withTrashed();
        }

        return $query->exists();
    }
}
