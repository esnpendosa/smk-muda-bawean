<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        Page::firstOrCreate(
            ['slug' => 'sejarah'],
            [
                'title' => 'Sejarah Sekolah',
                'content' => '<p>SMK Muda Bawean didirikan untuk memberikan pendidikan berkualitas tinggi bagi generasi muda di Pulau Bawean.</p>',
                'meta_title' => 'Sejarah SMK Muda Bawean',
                'meta_description' => 'Mengenal sejarah berdirinya SMK Muda Bawean dan perjalanannya dalam mendidik generasi penerus bangsa.',
            ]
        );

        Page::firstOrCreate(
            ['slug' => 'visi-misi'],
            [
                'title' => 'Visi & Misi',
                'content' => '<p><strong>Visi:</strong> Mewujudkan lulusan yang berakhlak mulia, kompeten, dan mandiri.</p><p><strong>Misi:</strong> Menyelenggarakan pendidikan kejuruan yang inovatif dan berorientasi pada kebutuhan industri.</p>',
                'meta_title' => 'Visi & Misi SMK Muda Bawean',
                'meta_description' => 'Visi dan Misi SMK Muda Bawean dalam melahirkan lulusan unggulan dan berdaya saing.',
            ]
        );
    }
}
