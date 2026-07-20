<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class ThemeService
{
    /**
     * Retrieve the theme colors from setting and cache them.
     */
    public function getColors(): array
    {
        return Cache::remember('theme_colors', 3600, function () {
            $primary = Setting::get('color_primary', '#16a34a');
            $secondary = Setting::get('color_secondary', '#15803d');
            $accent = Setting::get('color_accent', '#bbf7d0');

            return [
                'primary'         => $primary,
                'secondary'       => $secondary,
                'accent'          => $accent,
                'color_primary'   => $primary,
                'color_secondary' => $secondary,
                'color_accent'    => $accent,
            ];
        });
    }
}
