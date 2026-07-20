<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    private array $defaults = [
        'school_name'        => 'SMK Muhammadiyah 4 Sangkapura (SMK MUDA Bawean)',
        'school_address'     => 'JL. KH. Ahmad Dahlan No. 01, Daun, Sangkapura, Pulau Bawean, Kab. Gresik, Jawa Timur',
        'school_phone'       => '+62853-3324-5454',
        'school_email'       => 'smkmuda4bawean@gmail.com',
        'school_geo_lat'     => '-5.8468',
        'school_geo_lng'     => '112.6752',
        'color_primary'      => '#16a34a',
        'color_secondary'    => '#15803d',
        'color_accent'       => '#bbf7d0',
        'principal_name'     => 'Hasin Kholuqi, S.Pd',
        'principal_greeting' => '<p>Selamat datang di situs web resmi SMK Muhammadiyah 4 Sangkapura (SMK MUDA). Suatu kehormatan bagi kami menyambut Anda di rumah digital kami, tempat di mana kami berpegang teguh pada visi untuk mewujudkan sekolah yang Mandiri, Unggul, Disiplin, dan Agamis.</p><p>Melalui komitmen ini, kami berdedikasi membentuk generasi yang tidak hanya kompeten dan siap menghadapi tantangan zaman, tetapi juga memiliki karakter kuat yang berlandaskan akhlak mulia. Kami mengundang Anda untuk menjelajahi lebih jauh bagaimana kami membekali para siswa dengan perpaduan ilmu dan iman sebagai kunci masa depan mereka.</p>',
        'school_description' => 'SMK Muhammadiyah 4 Sangkapura (SMK MUDA Bawean) — sekolah menengah kejuruan terbaik di Pulau Bawean yang mencetak lulusan kompeten, berkarakter, dan siap bersaing global.',
        'robots_txt'         => "User-agent: *\nAllow: /\nDisallow: /admin/\n\nSitemap: https://smkmudabawean.sch.id/sitemap.xml",
        'ppdb_is_active'     => '1',
        'ppdb_start_date'    => '2026-07-01',
        'ppdb_end_date'      => '2026-08-31',
        'social_facebook'    => 'https://www.facebook.com/profile.php?id=100086197102754',
        'social_instagram'   => 'https://www.instagram.com/smkmudabawean',
        'social_youtube'     => '',
    ];

    public function run(): void
    {
        foreach ($this->defaults as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
