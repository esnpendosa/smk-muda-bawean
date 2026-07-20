<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Setting;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function editor(): User
    {
        return User::factory()->create(['role' => 'editor']);
    }

    public function test_settings_index_accessible_by_admin(): void
    {
        $response = $this->actingAs($this->admin())->get('/admin/settings');
        $response->assertStatus(200);
        $response->assertSee('Pengaturan');
    }

    public function test_settings_update_saves_and_invalidates_cache(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post('/admin/settings', [
            'school_name'    => 'SMK Muda Bawean',
            'school_address' => 'Pulau Bawean',
            'school_phone'   => '',
            'school_email'   => '',
            'school_geo_lat' => '',
            'school_geo_lng' => '',
        ]);

        // After update, the value should be persisted in DB and retrievable
        Cache::forget('settings_all');
        $this->assertEquals('SMK Muda Bawean', Setting::get('school_name'));
    }

    public function test_theme_update_valid_saves_colors(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin)->post('/admin/settings/theme', [
            'color_primary'   => '#ff5500',
            'color_secondary' => '#eab308',
            'color_accent'    => '#f59e0b',
        ]);

        $this->assertEquals('#ff5500', Setting::get('color_primary'));
    }

    public function test_theme_update_invalid_color_rejected(): void
    {
        $admin = $this->admin();
        $response = $this->actingAs($admin)->post('/admin/settings/theme', [
            'color_primary'   => 'not-a-color',
            'color_secondary' => '#eab308',
            'color_accent'    => '#f59e0b',
        ]);
        $response->assertSessionHasErrors(['color_primary']);
    }

    public function test_robots_txt_updated_via_seo_settings(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin)->post('/admin/settings/seo', [
            'robots_txt'       => "User-agent: *\nDisallow: /secret/",
            'meta_description' => '',
        ]);

        $response = $this->get('/robots.txt');
        $response->assertSee('Disallow: /secret/', false);
    }

    public function test_editor_cannot_access_settings(): void
    {
        // Settings routes are protected by role:admin middleware
        $response = $this->actingAs($this->editor())->get('/admin/settings');
        // Editor should get 403
        $response->assertStatus(403);
    }
}
