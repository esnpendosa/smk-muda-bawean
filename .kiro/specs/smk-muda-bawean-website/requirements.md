# Requirements Document

## Introduction

Proyek ini adalah migrasi dan rebuild website SMK Muda Bawean (https://smkmudabawean.sch.id/) dari WordPress ke Laravel Native. Website baru dibangun di atas shared hosting dengan MySQL, menggunakan arsitektur ringan berbasis Blade template + vanilla JS untuk halaman publik. Tujuan utamanya adalah meningkatkan performa, kemudahan pengelolaan konten, optimasi SEO/AEO, dan keamanan dibandingkan instalasi WordPress sebelumnya.

---

## Glossary

- **Website**: Aplikasi web publik SMK Muda Bawean yang dapat diakses oleh pengunjung umum.
- **Admin Panel**: Antarmuka manajemen konten yang hanya dapat diakses oleh pengguna terautentikasi dengan peran Administrator atau Editor.
- **Administrator**: Pengguna dengan akses penuh ke Admin Panel, termasuk pengaturan sistem.
- **Editor**: Pengguna dengan akses ke manajemen konten namun tidak dapat mengubah pengaturan sistem.
- **CMS**: Content Management System — bagian dari Admin Panel yang mengelola konten dinamis.
- **Pengunjung**: Pengguna tidak terautentikasi yang mengakses halaman publik Website.
- **Berita**: Konten artikel berbasis tanggal yang ditampilkan di halaman Home dan halaman daftar berita.
- **Pengumuman**: Pemberitahuan resmi sekolah yang ditampilkan di halaman Pengumuman dan Home.
- **Kelulusan**: Data hasil kelulusan siswa per tahun ajaran.
- **Alumni**: Lulusan SMK Muda Bawean yang dapat mendaftarkan diri dan mengisi Tracer Study.
- **PPDB**: Penerimaan Peserta Didik Baru — proses pendaftaran siswa baru secara online.
- **Tracer_Study**: Formulir survei yang diisi Alumni untuk melacak kegiatan pasca-kelulusan.
- **Slug**: Segmen URL ramah mesin pencari yang dihasilkan dari judul konten.
- **Schema_Markup**: Kode JSON-LD berdasarkan Schema.org yang disematkan di halaman untuk meningkatkan pemahaman mesin pencari.
- **Open_Graph**: Meta tag protokol Open Graph untuk pratinjau tautan di media sosial.
- **Sitemap**: File XML yang mendaftarkan semua URL publik Website untuk diindeks mesin pencari.
- **Theme_Color**: Nilai warna heksadesimal 6 digit (format `#RRGGBB`) yang dikonfigurasi melalui Admin Panel dan diterapkan ke seluruh tampilan Website.
- **Cache**: Mekanisme penyimpanan sementara hasil render halaman atau query database untuk mengurangi beban server.
- **Shared_Hosting**: Lingkungan hosting bersama dengan sumber daya CPU, RAM, dan koneksi database terbatas.

---

## Requirements

### Requirement 1: Halaman Home

**User Story:** Sebagai Pengunjung, saya ingin melihat halaman utama yang informatif, sehingga saya dapat mengetahui gambaran umum sekolah, berita terkini, dan pengumuman penting.

#### Acceptance Criteria

1. THE Website SHALL menampilkan halaman Home yang memuat Hero Section, daftar Berita terbaru, daftar Pengumuman terbaru, dan Sambutan Kepala Sekolah.
2. WHEN Pengunjung mengakses URL `/`, THE Website SHALL merender halaman Home dengan waktu response di bawah 2 detik pada kondisi Shared_Hosting.
3. THE Website SHALL menampilkan maksimal 6 Berita terbaru berdasarkan tanggal publikasi secara menurun pada Hero Section berita di halaman Home.
4. THE Website SHALL menampilkan maksimal 5 Pengumuman terbaru berdasarkan tanggal publikasi secara menurun pada bagian Pengumuman di halaman Home.
5. WHEN Administrator memperbarui konten Sambutan Kepala Sekolah melalui Admin Panel, THE Website SHALL menampilkan konten terbaru pada halaman Home tanpa memerlukan deployment ulang.
6. THE Website SHALL menampilkan halaman Home tanpa horizontal scrollbar dan dengan ukuran font minimal 14px pada semua breakpoint antara lebar layar 320px hingga 1920px.
7. WHEN Cache untuk halaman Home tersedia dan belum kedaluwarsa, THE Website SHALL menyajikan halaman dari Cache tanpa mengeksekusi ulang query database.
8. WHEN Cache untuk halaman Home tersedia namun sudah kedaluwarsa, THE Website SHALL menyajikan halaman dari Cache yang kedaluwarsa tersebut kepada Pengunjung, lalu meregenerasi Cache di background.
9. IF Cache untuk halaman Home tidak dapat dibaca karena kesalahan pembacaan Cache, THEN THE Website SHALL langsung mengeksekusi query database untuk merender halaman Home.
10. IF Cache untuk halaman Home tidak tersedia, THEN THE Website SHALL meregenerasi Cache setelah merender halaman.
11. IF jumlah Berita atau Pengumuman yang dipublikasikan adalah nol, THEN THE Website SHALL menampilkan pesan informatif pada bagian yang kosong dan tetap merender seluruh komponen halaman Home lainnya.

---

### Requirement 2: Profil Sekolah

**User Story:** Sebagai Pengunjung, saya ingin membaca informasi lengkap tentang sekolah, sehingga saya dapat memahami sejarah, visi misi, serta mengenal tenaga pendidik.

#### Acceptance Criteria

1. THE Website SHALL menyediakan halaman Profil Sekolah dengan tiga sub-halaman: Sejarah (`/profil/sejarah`), Visi & Misi (`/profil/visi-misi`), dan Pendidik & Tenaga Kependidikan (`/profil/pendidik`).
2. WHEN Pengunjung mengakses salah satu sub-halaman Profil yang valid, THE Website SHALL merender konten yang sesuai dari database dengan waktu response di bawah 2 detik.
3. THE Website SHALL menampilkan data Pendidik & Tenaga Kependidikan dalam bentuk daftar yang memuat nama, jabatan, dan foto profil.
4. IF foto profil Pendidik tidak tersedia, THEN THE Website SHALL menampilkan gambar placeholder default dengan atribut `alt` yang deskriptif berisi nama dan jabatan pendidik tersebut.
5. WHEN Administrator memperbarui konten sub-halaman Profil melalui Admin Panel, THE Website SHALL menampilkan konten terbaru pada halaman publik.
6. THE Website SHALL menyematkan Schema_Markup bertipe `EducationalOrganization` pada halaman `/profil/sejarah` dan `/profil/visi-misi` yang memuat nama sekolah, alamat, dan nomor telepon.
7. IF Pengunjung mengakses path di bawah `/profil/` yang tidak terdaftar (selain `sejarah`, `visi-misi`, `pendidik`), THEN THE Website SHALL mengembalikan HTTP status code 404 beserta halaman error 404.

---

### Requirement 3: Halaman Pengumuman

**User Story:** Sebagai Pengunjung, saya ingin membaca pengumuman resmi sekolah, sehingga saya dapat mengetahui informasi terbaru yang relevan.

#### Acceptance Criteria

1. THE Website SHALL menyediakan halaman daftar Pengumuman di URL `/pengumuman` yang menampilkan semua Pengumuman berstatus publik/aktif, diurutkan berdasarkan tanggal publikasi secara menurun.
2. WHEN Pengunjung mengklik satu Pengumuman, THE Website SHALL menavigasi ke halaman detail Pengumuman di URL `/pengumuman/{slug}`.
3. THE Website SHALL menampilkan halaman detail Pengumuman yang memuat judul, tanggal publikasi, isi konten lengkap, serta nama file dan ukuran file lampiran jika tersedia.
4. IF lampiran file tersedia pada Pengumuman, THEN THE Website SHALL menampilkan tautan unduh dengan nama file yang dapat diklik oleh Pengunjung.
5. IF lampiran file tidak tersedia pada Pengumuman, THEN THE Website SHALL tidak menampilkan bagian lampiran pada halaman detail Pengumuman.
6. THE Website SHALL menampilkan halaman daftar Pengumuman dengan pagination, menampilkan maksimal 10 Pengumuman per halaman.
7. WHEN Pengunjung mengakses halaman detail Pengumuman, THE Website SHALL menyematkan Schema_Markup bertipe `Announcement` yang memuat judul, tanggal, dan deskripsi singkat maksimal 160 karakter.
8. IF Pengunjung mengakses URL `/pengumuman/{slug}` dengan slug yang tidak terdaftar, THEN THE Website SHALL mengembalikan HTTP status code 404 beserta halaman error 404.

---

### Requirement 4: Halaman Kelulusan

**User Story:** Sebagai Pengunjung atau orang tua siswa, saya ingin melihat data hasil kelulusan, sehingga saya dapat mengetahui status kelulusan siswa pada tahun ajaran tertentu.

#### Acceptance Criteria

1. THE Website SHALL menyediakan halaman Kelulusan di URL `/kelulusan` yang menampilkan daftar tahun ajaran yang tersedia.
2. WHEN Pengunjung memilih tahun ajaran, THE Website SHALL menampilkan data Kelulusan untuk tahun ajaran tersebut.
3. THE Website SHALL menampilkan data Kelulusan yang memuat: nama siswa, nomor peserta ujian, program keahlian, dan status kelulusan dengan nilai eksplisit `LULUS` atau `TIDAK LULUS`.
4. THE Website SHALL menyediakan fitur pencarian berdasarkan nama siswa atau nomor peserta ujian pada halaman Kelulusan, dengan pencarian nama bersifat case-insensitive.
5. IF hasil pencarian tidak menemukan data yang cocok, THEN THE Website SHALL menampilkan pesan informatif dan tetap menampilkan form pencarian.
6. WHEN Administrator mengunggah data Kelulusan melalui Admin Panel dalam format CSV dengan kolom `nama_siswa`, `nomor_peserta`, `program_keahlian`, `status_kelulusan`, THE CMS SHALL mengimpor dan menyimpan data ke database.
7. IF format file yang diunggah bukan CSV atau kolom wajib (`nama_siswa`, `nomor_peserta`, `program_keahlian`, `status_kelulusan`) tidak ditemukan, THEN THE CMS SHALL menampilkan pesan error deskriptif yang menyebutkan kolom yang bermasalah dan menolak proses import.
8. IF file CSV mengandung sebagian baris dengan data tidak valid, THEN THE CMS SHALL mengimpor baris yang valid, menolak baris yang tidak valid, dan menampilkan laporan ringkasan jumlah baris berhasil dan baris gagal.
9. IF tahun ajaran yang dipilih tidak memiliki data Kelulusan, THEN THE Website SHALL menampilkan pesan informatif bahwa data belum tersedia untuk tahun ajaran tersebut.
10. THE Website SHALL menampilkan halaman Kelulusan tanpa mempublikasikan nomor induk kependudukan (NIK), tanggal lahir, atau data pribadi sensitif lainnya.

---

### Requirement 5: Halaman Alumni

**User Story:** Sebagai Alumni, saya ingin mendaftarkan diri dan mengisi Tracer Study, sehingga sekolah dapat melacak perkembangan lulusannya dan mempererat komunitas alumni.

#### Acceptance Criteria

1. THE Website SHALL menyediakan halaman Alumni di URL `/alumni` yang menampilkan informasi komunitas alumni dan formulir pendaftaran alumni.
2. WHEN Alumni mengisi dan mengirimkan formulir pendaftaran dengan data valid, THE Website SHALL menyimpan data alumni ke database dan menampilkan pesan konfirmasi.
3. THE Website SHALL memvalidasi formulir pendaftaran Alumni dengan memastikan: kolom nama lengkap terisi maksimal 100 karakter, kolom tahun lulus berupa angka antara 1990 hingga tahun berjalan, dan kolom alamat email berformat valid sesuai RFC 5322.
4. IF formulir pendaftaran Alumni dikirimkan dengan data tidak valid, THEN THE Website SHALL menampilkan pesan validasi per-kolom dan mempertahankan nilai kolom yang sudah terisi benar.
5. IF alamat email Alumni yang didaftarkan sudah tercatat di database, THEN THE Website SHALL menolak pendaftaran dan menampilkan pesan error pada kolom email bahwa alamat tersebut sudah terdaftar.
6. THE Website SHALL menyediakan halaman Tracer Study di URL `/alumni/tracer-study` yang memuat formulir survei kegiatan pasca-kelulusan.
7. WHEN Alumni mengirimkan formulir Tracer_Study dengan data valid yang mencakup nama lengkap, tahun lulus, status pendidikan lanjutan, dan status pekerjaan, THE Website SHALL menyimpan data survei ke database.
8. WHEN Administrator mengakses halaman statistik Tracer Study di Admin Panel, THE Website SHALL menampilkan ringkasan berupa jumlah responden, persentase yang melanjutkan pendidikan, dan persentase yang bekerja.
9. THE Website SHALL membatasi akses data detail individu Alumni hanya kepada pengguna Admin Panel yang terautentikasi.
10. THE Website SHALL tidak menyediakan endpoint publik yang mengembalikan data Alumni individual kepada Pengunjung yang tidak terautentikasi.

---

### Requirement 6: Pendaftaran Siswa Baru (PPDB)

**User Story:** Sebagai calon siswa atau orang tua, saya ingin mendaftar secara online, sehingga proses pendaftaran dapat dilakukan kapan saja tanpa harus datang langsung ke sekolah.

#### Acceptance Criteria

1. THE Website SHALL menyediakan halaman PPDB di URL `/ppdb` yang menampilkan informasi jadwal, persyaratan, dan formulir pendaftaran online.
2. WHEN Pengunjung mengisi dan mengirimkan formulir PPDB dengan data valid, THE Website SHALL menyimpan data pendaftaran ke database dan menampilkan nomor registrasi unik.
3. THE Website SHALL memvalidasi formulir PPDB dengan memastikan kolom berikut terisi dan valid: nama lengkap (maks. 100 karakter), tempat lahir (maks. 50 karakter), tanggal lahir (format YYYY-MM-DD), asal sekolah (maks. 100 karakter), nama orang tua/wali (maks. 100 karakter), dan nomor telepon (10–13 digit angka).
4. IF formulir PPDB dikirimkan dengan data tidak valid, THEN THE Website SHALL menampilkan pesan validasi per-kolom dan mempertahankan nilai kolom yang sudah terisi benar.
5. THE Website SHALL menghasilkan nomor registrasi PPDB dengan format `PPDB-{YYYY}{MM}{DD}-{nomor_urut_4_digit}` yang unik dan tidak duplikat untuk setiap pendaftaran yang berhasil disimpan.
6. WHEN Administrator mengakses daftar PPDB di Admin Panel, THE Website SHALL menampilkan semua pendaftar dengan kemampuan filter berdasarkan status (`menunggu`, `diterima`, `ditolak`) dan tombol ekspor ke format CSV.
7. WHILE periode PPDB tidak aktif, THE Website SHALL menampilkan halaman PPDB dengan informasi bahwa pendaftaran belum/sudah dibuka, dan tidak menampilkan formulir pendaftaran.
8. WHEN Administrator mengubah status periode PPDB melalui Admin Panel, THE Website SHALL segera menerapkan perubahan tersebut pada halaman publik `/ppdb`.
9. THE Website SHALL menyematkan Schema_Markup bertipe `Event` pada halaman PPDB yang memuat nama kegiatan, tanggal mulai, dan tanggal selesai pendaftaran.

---

### Requirement 7: Manajemen Berita

**User Story:** Sebagai Administrator atau Editor, saya ingin mengelola konten berita melalui Admin Panel, sehingga informasi terbaru sekolah dapat dipublikasikan dengan mudah.

#### Acceptance Criteria

1. THE CMS SHALL menyediakan antarmuka untuk membuat, membaca, memperbarui, dan menghapus (CRUD) data Berita.
2. WHEN Administrator atau Editor menyimpan Berita baru, THE CMS SHALL secara otomatis menghasilkan Slug dari judul Berita yang unik di dalam database.
3. THE CMS SHALL memungkinkan Administrator atau Editor mengunggah gambar thumbnail berformat JPG, PNG, atau WebP dengan ukuran maksimal 2MB untuk setiap Berita.
4. THE CMS SHALL menyediakan status publikasi Berita: `draft` (tidak tampil publik) dan `published` (tampil publik).
5. WHEN Administrator atau Editor mengubah status Berita dari `draft` ke `published`, THE Website SHALL menampilkan Berita tersebut di halaman publik.
6. WHEN Administrator atau Editor mengubah status Berita yang sudah `published` kembali ke `draft`, THE Website SHALL tidak lagi menampilkan Berita tersebut di halaman publik.
7. THE Website SHALL menyematkan Schema_Markup bertipe `NewsArticle` pada setiap halaman detail Berita yang memuat judul, tanggal terbit, penulis, dan gambar.
8. THE Website SHALL menyediakan halaman detail Berita di URL `/berita/{slug}`.
9. IF Pengunjung mengakses URL `/berita/{slug}` dengan slug yang tidak terdaftar, THEN THE Website SHALL mengembalikan HTTP status code 404 beserta halaman error 404.
10. IF Administrator atau Editor menghapus Berita yang sudah dipublikasikan, THEN THE Website SHALL mengembalikan HTTP status code 410 (Gone) pada URL Berita yang dihapus.

---

### Requirement 8: Admin Panel & Autentikasi

**User Story:** Sebagai Administrator, saya ingin mengakses Admin Panel yang aman, sehingga hanya pengguna yang berwenang yang dapat mengelola konten website.

#### Acceptance Criteria

1. IF pengguna terautentikasi mencoba mengakses resource Admin Panel di luar hak aksesnya berdasarkan peran, THEN THE Admin Panel SHALL mengembalikan HTTP status code 403 (Forbidden).
2. WHEN pengguna yang tidak terautentikasi mengakses URL di bawah `/admin/*`, THE Admin Panel SHALL mengarahkan pengguna ke halaman login.
3. THE Admin Panel SHALL mengimplementasikan proteksi CSRF pada semua formulir yang memproses perubahan data.
4. WHEN pengguna dengan peran Administrator berhasil login, THE Admin Panel SHALL mengarahkan ke dashboard Administrator. WHEN pengguna dengan peran Editor berhasil login, THE Admin Panel SHALL mengarahkan ke dashboard Editor dengan menu sesuai hak akses Editor.
5. IF pengguna salah memasukkan kredensial sebanyak 5 kali berturut-turut, THEN THE Admin Panel SHALL mengunci akun selama 15 menit dan menampilkan pesan informasi kepada pengguna.
6. WHEN pengguna mengklik tombol logout, THE Admin Panel SHALL menginvalidasi sesi aktif dan mengarahkan pengguna ke halaman login.
7. THE Admin Panel SHALL menampilkan dashboard yang merangkum statistik: jumlah Berita, jumlah Pengumuman, jumlah pendaftar PPDB, dan jumlah Alumni terdaftar.
8. WHEN sesi pengguna tidak menerima request selama 60 menit, THE Admin Panel SHALL menginvalidasi sesi dan meminta login ulang pada request berikutnya.

---

### Requirement 9: Pengaturan SEO & Schema Markup

**User Story:** Sebagai Administrator, saya ingin mengkonfigurasi meta tag dan structured data melalui Admin Panel, sehingga setiap halaman website dapat diindeks secara optimal oleh mesin pencari.

#### Acceptance Criteria

1. THE CMS SHALL menyediakan kolom input untuk meta title (maks. 60 karakter) dan meta description (maks. 160 karakter) pada setiap entitas konten yang dapat diedit (Berita, Pengumuman, halaman statis).
2. THE Website SHALL menyematkan meta tag `<title>`, `<meta name="description">`, dan Open_Graph tags (`og:title`, `og:description`, `og:image`, `og:url`) pada setiap halaman publik.
3. IF meta title tidak diisi pada entitas konten, THEN THE Website SHALL menggunakan judul konten sebagai meta title secara otomatis.
4. IF meta description tidak diisi pada entitas konten, THEN THE Website SHALL menghasilkan meta description otomatis dari 160 karakter pertama teks konten tanpa tag HTML.
5. THE Website SHALL menyediakan file Sitemap dalam format XML valid di URL `/sitemap.xml` yang mendaftarkan semua URL konten berstatus publik.
6. WHEN konten baru dipublikasikan atau konten dihapus, THE Website SHALL memperbarui Sitemap dalam waktu tidak lebih dari 60 detik.
7. THE Website SHALL menyediakan file `robots.txt` yang dapat dikonfigurasi kontennya melalui Admin Panel di URL `/robots.txt`.
8. THE Website SHALL menyematkan Schema_Markup bertipe `FAQPage` pada halaman yang memiliki seksi FAQ yang dikelola melalui Admin Panel.
9. IF semua data lokasi (nama sekolah, alamat lengkap, koordinat geografis, nomor telepon) telah terisi di Admin Panel, THEN THE Website SHALL menyematkan Schema_Markup bertipe `LocalBusiness` pada setiap halaman publik.
10. THE Website SHALL hanya merender Schema_Markup ketika seluruh field wajib dalam schema tersebut telah terisi, sehingga output JSON-LD yang dihasilkan selalu valid sesuai spesifikasi Schema.org.

---

### Requirement 10: Pengaturan Tema & Warna

**User Story:** Sebagai Administrator, saya ingin mengubah warna tema website melalui Admin Panel, sehingga tampilan website dapat disesuaikan tanpa memerlukan perubahan kode.

#### Acceptance Criteria

1. THE Admin Panel SHALL menyediakan antarmuka pengaturan tema dengan color picker yang memungkinkan Administrator mengkonfigurasi minimal tiga nilai Theme_Color dalam format `#RRGGBB`: warna primer, warna sekunder, dan warna aksen.
2. WHEN Administrator menyimpan konfigurasi Theme_Color baru, THE Website SHALL menerapkan warna tersebut ke seluruh halaman publik tanpa memerlukan deployment ulang.
3. THE Website SHALL merender variabel CSS custom properties (`--color-primary`, `--color-secondary`, `--color-accent`) hanya dari nilai Theme_Color yang tersimpan valid di database.
4. THE Website SHALL memastikan rasio kontras warna teks terhadap latar belakang memenuhi standar WCAG 2.1 Level AA (minimal 4.5:1 untuk teks normal berukuran di bawah 18pt, dan minimal 3:1 untuk teks besar berukuran 18pt ke atas atau bold 14pt ke atas).
5. IF nilai Theme_Color yang dimasukkan bukan kode heksadesimal 6 digit yang valid (tidak sesuai format `#RRGGBB`), THEN THE Admin Panel SHALL menampilkan pesan validasi dan menolak penyimpanan.
6. THE Admin Panel SHALL menampilkan pratinjau langsung (live preview) yang mencerminkan perubahan warna pada komponen representatif (header, tombol primer, teks body) sebelum Administrator menyimpan konfigurasi.

---

### Requirement 11: Performa & Caching

**User Story:** Sebagai Pengunjung, saya ingin website dapat dimuat dengan cepat, sehingga pengalaman browsing tetap nyaman meskipun dijalankan di Shared_Hosting.

#### Acceptance Criteria

1. THE Website SHALL mengimplementasikan Cache pada halaman Home, Profil Sekolah (`/profil/*`), dan halaman statis dengan durasi Cache minimal 60 menit.
2. WHEN Administrator menyimpan perubahan konten melalui Admin Panel, THE Website SHALL menginvalidasi Cache yang terkait dengan konten yang diperbarui dalam waktu tidak lebih dari 5 detik.
3. THE Website SHALL mengaktifkan kompresi gzip atau brotli untuk respons teks (HTML, CSS, JS) dan menetapkan header `Cache-Control: max-age=31536000` untuk aset statis yang di-fingerprint.
4. THE Website SHALL mengimplementasikan lazy loading untuk gambar pada semua halaman publik menggunakan atribut HTML `loading="lazy"`.
5. THE Website SHALL menggunakan indeks database pada kolom: `posts.published_at`, `posts.status`, `posts.slug`, `announcements.published_at`, `announcements.status`, `announcements.slug`, dan `graduations.academic_year`.
6. THE Website SHALL membatasi jumlah query database per request halaman publik tidak melebihi 10 query untuk halaman sederhana dan 20 query untuk halaman dengan relasi data kompleks.

---

### Requirement 12: Keamanan Aplikasi

**User Story:** Sebagai Administrator, saya ingin website terlindungi dari serangan umum, sehingga data sekolah dan pengunjung tetap aman.

#### Acceptance Criteria

1. THE Website SHALL menggunakan Eloquent ORM atau prepared statements untuk semua query database pada formulir publik dan Admin Panel, sehingga tidak ada nilai input pengguna yang diinterpolasi langsung ke dalam string SQL.
2. THE Website SHALL mengimplementasikan proteksi CSRF token pada semua formulir publik yang mengirimkan data (PPDB, Alumni, Tracer Study).
3. THE Website SHALL membatasi ukuran file yang dapat diunggah melalui formulir publik maksimal 2MB per file.
4. THE Website SHALL memvalidasi tipe MIME file yang diunggah dan hanya mengizinkan: `application/pdf`, `image/jpeg`, `image/png` untuk dokumen; `text/csv` untuk data import.
5. THE Website SHALL menyimpan file yang diunggah di luar direktori `public/` dengan nama file acak yang tidak dapat ditebak (menggunakan UUID atau hash acak).
6. THE Admin Panel SHALL mengimplementasikan rate limiting pada endpoint login, menolak request dari satu IP address yang melebihi 10 request per menit selama 60 detik.
7. IF konten rich text yang diinput melalui Admin Panel mengandung tag `<script>`, `<iframe>`, `<object>`, `<embed>`, atau atribut event handler (seperti `onclick`, `onerror`, `onload`), THEN THE CMS SHALL menghapus tag dan atribut tersebut sebelum menyimpan ke database, sementara konten yang hanya mengandung tag yang diizinkan disimpan tanpa modifikasi.

---

### Requirement 13: Aksesibilitas

**User Story:** Sebagai Pengunjung dengan kebutuhan khusus, saya ingin website dapat diakses menggunakan berbagai perangkat dan teknologi bantu, sehingga informasi sekolah dapat dijangkau oleh semua kalangan.

#### Acceptance Criteria

1. THE Website SHALL menyertakan atribut `alt` yang deskriptif pada setiap elemen `<img>` yang bersifat informatif, dan atribut `alt=""` (kosong) pada gambar dekoratif di seluruh halaman publik.
2. THE Website SHALL memastikan semua elemen interaktif (tombol, tautan, input formulir) dapat difokuskan menggunakan keyboard dengan urutan tab yang logis dan memiliki indikator fokus visual yang terlihat.
3. THE Website SHALL menggunakan elemen HTML semantik yang tepat (`<header>`, `<nav>`, `<main>`, `<article>`, `<footer>`) untuk struktur setiap halaman.
4. THE Website SHALL memastikan setiap halaman memiliki hierarki heading yang benar dimulai dari `<h1>` dan tidak melewati level (tidak dari `<h1>` langsung ke `<h3>`).
5. THE Website SHALL menyertakan atribut `lang="id"` pada elemen `<html>` untuk menandai bahasa utama halaman.

---

### Requirement 14: Parser & Serializer Konten

**User Story:** Sebagai sistem, saya ingin konten yang disimpan dapat dirender dan diekspor dengan konsisten, sehingga tidak ada kehilangan atau kerusakan data saat konten diproses.

#### Acceptance Criteria

1. THE CMS SHALL menyimpan konten rich text dalam format HTML yang telah disanitasi di database.
2. WHEN konten HTML disimpan dan kemudian dibaca kembali dari database, THE Website SHALL merender output yang memiliki tag HTML dan konten teks yang identik dengan yang disimpan (round-trip property).
3. THE CMS SHALL menghasilkan Slug dari judul konten dengan mengubah huruf menjadi lowercase, mengganti spasi dengan tanda hubung, menghapus karakter non-alfanumerik, dan memastikan keunikan Slug dalam tipe konten yang sama.
4. THE CMS SHALL menghasilkan Slug secara deterministik sehingga judul konten yang sama selalu menghasilkan Slug yang sama sebelum penambahan suffix keunikan.
5. WHEN data Kelulusan diimpor dari CSV dan kemudian diekspor kembali ke CSV, THE CMS SHALL menghasilkan file CSV yang memuat nilai identik pada setiap kolom dan baris dengan file asal (round-trip CSV property).
6. THE Website SHALL menghasilkan output JSON-LD yang merupakan JSON valid yang dapat di-parse oleh parser JSON standar pada setiap halaman yang menyematkan Schema_Markup.

---

## Correctness Properties (untuk Property-Based Testing)

### P1: Slug Generation — Idempotence
Menghasilkan Slug dari sebuah judul dua kali berturut-turut SHALL menghasilkan Slug yang identik.
`generate_slug(title) == generate_slug(generate_slug(title))`

### P2: Slug Generation — Karakter Valid
Untuk semua judul dengan panjang 1–255 karakter, Slug yang dihasilkan SHALL hanya mengandung karakter `[a-z0-9\-]` dan tidak dimulai atau diakhiri dengan tanda hubung.

### P3: HTML Sanitization — Safety Invariant
Untuk semua string input yang mengandung tag `<script>`, `<iframe>`, `<object>`, `<embed>`, atau atribut event handler (`onclick`, `onerror`, `onload`, dll.), output fungsi sanitasi SHALL tidak mengandung string tersebut.

### P4: HTML Sanitization — Content Preservation
Untuk semua input HTML yang hanya mengandung tag yang diizinkan (seperti `<p>`, `<b>`, `<ul>`, `<li>`, `<a>`), fungsi sanitasi SHALL mempertahankan konten teks dan struktur tag yang diizinkan tanpa modifikasi.

### P5: Nomor Registrasi PPDB — Uniqueness
Untuk setiap batch N pendaftaran PPDB yang berhasil disimpan secara bersamaan, jumlah nomor registrasi unik SHALL sama dengan N.

### P6: Schema Markup — Valid JSON
Untuk semua halaman publik yang menyematkan Schema_Markup, output JSON-LD SHALL dapat di-parse oleh parser JSON standar (round-trip: `json_decode(json_encode(schema)) == schema`).

### P7: Theme Color — CSS Variable Consistency
Untuk semua konfigurasi Theme_Color valid yang disimpan ke database, nilai yang dirender dalam CSS custom properties SHALL identik dengan nilai yang tersimpan (round-trip: `parse_css_value(render_css(color)) == color`).

### P8: CSV Import/Export — Round Trip
Untuk semua data Kelulusan valid yang diimpor dari CSV, mengekspor data tersebut kembali ke CSV SHALL menghasilkan file dengan nilai identik pada setiap kolom dan baris dengan file asal.

### P9: Pagination — Completeness
Untuk semua daftar konten dengan N item yang dipaginasi dengan ukuran halaman P, menjumlahkan jumlah item di semua halaman SHALL menghasilkan N, dan tidak ada item yang muncul lebih dari satu kali di seluruh halaman.

### P10: Cache Invalidation — Consistency
WHEN konten diperbarui dan Cache diinvalidasi dalam waktu ≤5 detik, semua request berikutnya ke halaman terkait SHALL mengembalikan konten yang mencerminkan pembaruan terbaru.
