<?php

namespace Tests\Feature\Property;

use App\Models\Setting;
use App\Services\ThemeService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThemeColorPropertyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * P7: nilai color yang disimpan = nilai yang dirender di CSS
     */
    public function test_theme_color_roundtrip(): void
    {
        $validColors = ['#16a34a', '#15803d', '#bbf7d0', '#000000', '#ffffff', '#FF0000'];

        foreach ($validColors as $color) {
            Setting::set('color_primary', $color);
            $rendered = app(ThemeService::class)->getColors()['color_primary'];
            $this->assertSame(strtolower($color), strtolower($rendered),
                "Warna yang dirender berbeda dari yang disimpan untuk: {$color}");
        }
    }
}
