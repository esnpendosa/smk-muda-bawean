<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $this->command->warn('No admin user found. Run AdminUserSeeder first.');
            return;
        }

        $posts = [
            [
                'title'        => 'Hadapi Tantangan Era Artificial Intelligence (AI), SMK MUDA Bawean Gelar Parenting Bersama Wali Murid',
                'content'      => '<p>smkmudabawean – Menyadari pentingnya keselarasan pendidikan di sekolah dan di rumah, SMK Muhammadiyah 4 (SMK MUDA) Sangkapura Bawean menggelar kegiatan Parenting pada Kamis, 16 Juli 2026. Acara yang bertempat di Aula SMK MUDA Bawean ini dihadiri langsung oleh jajaran guru beserta seluruh orang tua/wali murid dengan antusiasme yang tinggi.</p><p>Pihak sekolah menyampaikan bahwa agenda parenting ini merupakan bagian dari komitmen sekolah untuk melibatkan orang tua secara aktif dalam proses pendidikan anak, khususnya di era kecerdasan buatan (Artificial Intelligence/AI) yang kian berkembang pesat. Narasumber memaparkan berbagai strategi konkret bagi orang tua dalam mendampingi anak menghadapi tantangan era digital dengan bijaksana.</p>',
                'published_at' => '2026-07-16 08:00:00',
                'category'     => 'Berita & Pengumuman',
            ],
            [
                'title'        => 'Usung Tema "Gembira Berkarya", FORTASI SMK MUDA Bawean Sambut Murid Baru dengan Penuh Semangat',
                'content'      => '<p>smkmudabawean – Suasana ceria dan penuh energi positif menyelimuti lapangan utama SMK Muhammadiyah 4 (SMK MUDA) Sangkapura Bawean pada Senin pagi (13/07/2026). Seluruh warga sekolah berkumpul secara khidmat untuk mengikuti apel pembukaan Forum Ta\'aruf dan Orientasi Siswa Baru (FORTASI) Tahun Ajaran 2026/2027.</p><p>Apel akbar ini dihadiri langsung oleh Kepala Sekolah, seluruh jajaran guru dan tenaga kependidikan, serta ratusan siswa baru yang tampak antusias menyambut hari pertama mereka. Tema "Gembira Berkarya" dipilih sebagai semangat penyambutan, mencerminkan harapan agar siswa baru dapat menemukan passion mereka dan berkarya dengan penuh kegembiraan selama menempuh pendidikan di SMK MUDA.</p>',
                'published_at' => '2026-07-13 07:00:00',
                'category'     => 'Kegiatan Sekolah',
            ],
            [
                'title'        => 'Libur Sekolah Tetap Buka || SPMB SMK MUDA Bawean Layani Pendaftaran Murid Baru dan Mutasi',
                'content'      => '<p>smkmudabawean – Memasuki masa libur panjang kenaikan kelas, aktivitas layanan administrasi di SMK Muhammadiyah 4 (SMK MUDA) Sangkapura Bawean dipastikan tetap berjalan normal. Sekolah yang terletak di Pulau Bawean ini berkomitmen penuh memfasilitasi para lulusan tingkat pertama yang ingin melanjutkan pendidikan vokasi melalui Sistem Penerimaan Murid Baru (SPMB) Tahun Ajaran 2026/2027.</p><p>Sesuai kalender akademik, libur panjang kenaikan kelas tidak serta-merta menghentikan proses layanan penerimaan siswa baru. Panitia SPMB tetap stand-by di kantor sekolah setiap hari kerja untuk menerima berkas pendaftaran, melayani konsultasi, dan menjawab pertanyaan calon peserta didik maupun orang tua.</p>',
                'published_at' => '2026-06-25 08:00:00',
                'category'     => 'Berita & Pengumuman',
            ],
            [
                'title'        => 'Pemanfaatan Teknologi di Genggaman || SMK MUDA Bawean Sukses Gelar PSAT 2026 Berbasis Android',
                'content'      => '<p>Smkmudabawean.sch.id – Satu lagi agenda krusial dalam kalender akademik SMK Muhammadiyah 4 (SMK MUDA) Sangkapura Bawean berhasil dituntaskan dengan gemilang. Rangkaian Penilaian Sumatif Akhir Tahun (PSAT) Tahun Ajaran 2025/2026 yang berlangsung selama sepekan, mulai dari tanggal 20 hingga 26 Mei 2026, resmi ditutup dengan status sukses total tanpa kendala teknis yang berarti.</p><p>Berbeda dengan evaluasi tengah semester yang masih menggunakan metode konvensional, PSAT 2026 ini sepenuhnya berbasis aplikasi Android yang diinstal pada perangkat masing-masing siswa. Inovasi ini memungkinkan siswa mengerjakan soal dengan lebih nyaman dan efisien, sekaligus melatih kompetensi digital mereka sejak dini.</p>',
                'published_at' => '2026-05-27 09:00:00',
                'category'     => 'Akademik',
            ],
            [
                'title'        => 'Perkuat Mutu Pendidikan Vokasi Kepulauan, Pengawas Cabdin Gresik Gelar Pembinaan Intensif di SMK Muhammadiyah 4 Sangkapura',
                'content'      => '<p>smkmudabawean – Komitmen untuk terus meningkatkan mutu pendidikan dan tata kelola kelembagaan kembali ditunjukkan oleh keluarga besar SMK Muhammadiyah 4 (SMK MUDA) Sangkapura Bawean. Pada hari Selasa, 19 Mei 2026, sekolah menyelenggarakan agenda penting berupa Pembinaan oleh Pengawas Sekolah SMK yang dipusatkan di Laboratorium Komputer sekolah.</p><p>Kegiatan strategis ini dihadiri langsung oleh Kepala Sekolah, seluruh dewan guru, dan tenaga kependidikan. Pengawas dari Cabang Dinas Pendidikan Gresik memaparkan berbagai aspek yang menjadi fokus pembinaan, mulai dari implementasi kurikulum Merdeka Belajar, penguatan manajemen sekolah, hingga strategi peningkatan kualitas pembelajaran berbasis kompetensi.</p>',
                'published_at' => '2026-05-19 10:00:00',
                'category'     => 'Berita & Pengumuman',
            ],
            [
                'title'        => 'Jelajahi Daratan Gresik, Siswa TKR SMK MUDA Bawean Sukses Laksanakan Studi Banding "Siap Kerja Sebelum Lulus"',
                'content'      => '<p>smkmudabawean – Semangat untuk meningkatkan kompetensi dan memperluas wawasan industri membawa rombongan SMK Muhammadiyah 4 (SMK MUDA) Sangkapura menempuh perjalanan panjang melintasi laut Jawa. Siswa Kelas X Program Keahlian Teknik Kendaraan Ringan Otomotif (TKR) melaksanakan kegiatan Studi Banding ke daratan Gresik pada 11–13 Mei 2026.</p><p>Perjalanan dimulai pada Senin malam (11/05) dari Pulau Bawean menuju Pelabuhan Gresik menggunakan kapal feri. Sesampainya di Gresik, rombongan langsung menuju beberapa bengkel otomotif dan industri yang telah menjadi mitra sekolah untuk mendapatkan pengalaman langsung tentang dunia kerja sesungguhnya.</p>',
                'published_at' => '2026-05-13 14:00:00',
                'category'     => 'Kegiatan Sekolah',
            ],
            [
                'title'        => 'Menuju Musyran Ke – IX Tahun 2026 IPM SMK MUDA Bawean || Ajang Pembelajaran Demokrasi dan Regenerasi Kepemimpinan',
                'content'      => '<p>smkmudabawean – Geliat demokrasi mulai terasa di lingkungan SMK Muhammadiyah 4 (SMK MUDA) Sangkapura Bawean. Organisasi otonom Ikatan Pelajar Muhammadiyah (IPM) bersiap menyelenggarakan Musyawarah Ranting (Musyran) Ke-IX Tahun 2026 yang akan dilaksanakan pada 27 hingga 28 April 2026.</p><p>Agenda ini bukan sekadar pergantian pengurus, melainkan laboratorium nyata bagi siswa untuk belajar berdemokrasi sejak dini. Dengan mengusung tema besar yang mencerminkan semangat regenerasi kepemimpinan islami, Musyran kali ini diharapkan dapat melahirkan kader-kader muda IPM yang tangguh, amanah, dan berdedikasi tinggi untuk kemajuan organisasi dan sekolah.</p>',
                'published_at' => '2026-04-25 08:00:00',
                'category'     => 'Kegiatan Sekolah',
            ],
            [
                'title'        => 'Semarak Hari Kartini di SMK MUDA Bawean || "Harmoni Warna Kartini" Warnai Jalanan Dusun Daun Barat',
                'content'      => '<p>smkmudabawean – Nuansa berbeda tampak di sepanjang jalan raya Dusun Daun Barat, Sangkapura, pada pagi hari ini. Serentak siswa dan guru SMK Muhammadiyah 4 (SMK MUDA) Sangkapura Bawean turun ke jalan dengan balutan busana adat dan kebaya yang anggun dalam rangka memperingati Hari Kartini 2026 yang bertepatan pada Hari Selasa 21 April 2026.</p><p>Mengusung tema "Harmoni Warna Kartini", pawai budaya ini melibatkan seluruh warga sekolah dari berbagai jurusan. Para siswi tampil memukau dengan mengenakan kebaya dan pakaian tradisional dari berbagai daerah di Indonesia, sementara para siswa ikut serta dengan busana adat pendamping yang serasi dan penuh makna.</p>',
                'published_at' => '2026-04-21 07:30:00',
                'category'     => 'Kegiatan Sekolah',
            ],
            [
                'title'        => 'Satu Pekan Penuh Makna || SMK MUDA Bawean Sukses Gelar PSAJ 2026 Berbasis Digital',
                'content'      => '<p>smkmudabawean – Keluarga besar SMK Muhammadiyah 4 (SMK MUDA) Sangkapura Bawean bernapas lega dan penuh syukur. Penyelenggaraan Penilaian Sumatif Akhir Jenjang (PSAJ) Tahun Pelajaran 2025/2026 yang berlangsung sejak tanggal 13 hingga 17 April 2026 resmi berakhir dengan sukses dan lancar.</p><p>Pelaksanaan ujian yang dipusatkan di Laboratorium Komputer sekolah ini menjadi bukti nyata kesiapan mental dan infrastruktur digital SMK MUDA Bawean dalam menghadapi era evaluasi berbasis teknologi. Seluruh siswa kelas XII mengikuti ujian dengan tertib dan penuh tanggung jawab.</p>',
                'published_at' => '2026-04-18 10:00:00',
                'category'     => 'Akademik',
            ],
            [
                'title'        => 'SMK MUDA Bawean Sampaikan Pesan Idulfitri 1447 H yang Menyentuh Hati',
                'content'      => '<p>smkmudabawean – Suasana khidmat menyelimuti Pulau Bawean pada Jum\'at 20 Maret 2026 yang bertepatan 1 Syawal 1447 H, seiring dengan berkumandangnya takbir kemenangan. Keluarga besar SMK Muhammadiyah 4 (SMK MUDA) Sangkapura secara resmi menyampaikan ucapan selamat Idulfitri 1447 Hijriah melalui berbagai kanal informasi sekolah.</p><p>Pesan lebaran tahun ini dikemas dengan penuh ketulusan dan kehangatan, mencerminkan nilai-nilai Islam yang menjadi landasan pendidikan di SMK MUDA. Seluruh civitas akademika mengucapkan Minal Aidin Wal Faizin, mohon maaf lahir dan batin.</p>',
                'published_at' => '2026-03-20 08:00:00',
                'category'     => 'Berita & Pengumuman',
            ],
            [
                'title'        => 'PSAJ 2026 || SMK MUDA Bawean Tegaskan Eksistensi Pendidikan Berbasis Digital di Pulau Bawean',
                'content'      => '<p>smkmudabawean – Pelaksanaan Penilaian Sumatif Akhir Jenjang (PSAJ) Tahun Ajaran 2025/2026 di SMK Muhammadiyah 4 Sangkapura atau yang sering kita kenal dengan nama SMK MUDA Bawean menjadi bukti nyata adaptasi teknologi di dunia pendidikan kepulauan.</p><p>Dimulai pada Senin, 13 April 2026, ujian ini tidak hanya menjadi tolok ukur akademik, tetapi juga momentum pembuktian kualitas teknologi sekolah dalam menyelenggarakan evaluasi berbasis digital yang setara dengan sekolah-sekolah di daratan.</p>',
                'published_at' => '2026-04-13 09:00:00',
                'category'     => 'Akademik',
            ],
            [
                'title'        => 'Milad ke-12 Tahun 2026 SMK MUDA Bawean || Manifestasi "Level Up 12" Melalui Aksi Berbagi 140 Paket Sembako',
                'content'      => '<p>smkmudabawean – Dalam rangka memperingati Milad ke-12 SMK Muhammadiyah 4 (SMK MUDA) Sangkapura Bawean, sekolah menggelar aksi sosial yang penuh makna dengan tema "Level Up 12". Sebanyak 140 paket sembako dibagikan kepada warga sekitar dan keluarga tidak mampu di sekitar lingkungan sekolah.</p><p>Kegiatan ini merupakan wujud nyata dari nilai-nilai islami yang selalu ditanamkan di SMK MUDA, bahwa kemajuan sekolah harus sejalan dengan kepedulian terhadap masyarakat sekitar. Para siswa dilibatkan langsung dalam proses persiapan hingga distribusi paket sembako sebagai bentuk pendidikan karakter sosial.</p>',
                'published_at' => '2026-03-10 09:00:00',
                'category'     => 'Kegiatan Sekolah',
            ],
            [
                'title'        => 'SMK Muhammadiyah 4 Sangkapura Gelar UKK 2026 || Cetak Lulusan Kompeten dan Siap Mental',
                'content'      => '<p>smkmudabawean – Memasuki babak akhir masa studi, siswa kelas 12 SMK Muhammadiyah 4 (SMK MUDA) Sangkapura, Bawean menjalani Ujian Kompetensi Keahlian (UKK) sebagai ajang pembuktian skill sebelum melangkah ke dunia profesional.</p><p>Kegiatan ini berlangsung intensif selama tiga hari, mulai tanggal 11 hingga 13 Februari 2026. Tahun ini, dua jurusan unggulan, yaitu Teknik Kendaraan Ringan Otomotif (TKRO) dan Akuntansi dan Keuangan Lembaga (AKL), menampilkan kompetensi terbaik mereka di hadapan penguji dari industri dan lembaga sertifikasi profesi.</p>',
                'published_at' => '2026-02-13 11:00:00',
                'category'     => 'Akademik',
            ],
        ];

        foreach ($posts as $data) {
            $slug = Str::slug($data['title']);
            // Ensure unique slug
            $count = 1;
            $baseSlug = $slug;
            while (Post::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $count++;
            }

            Post::create([
                'title'            => $data['title'],
                'slug'             => $slug,
                'content'          => $data['content'],
                'status'           => 'published',
                'author_id'        => $admin->id,
                'published_at'     => $data['published_at'],
                'meta_title'       => mb_substr($data['title'], 0, 58),
                'meta_description' => mb_substr(strip_tags($data['content']), 0, 155),
            ]);
        }

        $this->command->info('✅ ' . count($posts) . ' artikel berhasil di-seed dari smkmudabawean.sch.id');
    }
}
