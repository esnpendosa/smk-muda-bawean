<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    private array $defaults = [
        'school_name'        => 'SMK Muda Bawean',
        'school_address'     => 'Jl. Raya Bawean, Sangkapura, Kab. Gresik, Jawa Timur',
        'school_phone'       => '',
        'school_email'       => 'info@smkmudabawean.sch.id',
        'school_geo_lat'     => '',
        'school_geo_lng'     => '',
        'color_primary'      => '#16a34a',
        'color_secondary'    => '#15803d',
        'color_accent'       => '#bbf7d0',
        'principal_greeting' => '',
        'robots_txt'         => "User-agent: *\nAllow: /\nDisallow: /admin/",
        'ppdb_is_active'     => '0',
        'ppdb_start_date'    => '',
        'ppdb_end_date'      => '',
        'social_facebook'    => '',
        'social_instagram'   => '',
        'social_youtube'     => '',
    ];

    public function run(): void
    {
        foreach ($this->defaults as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
