# Implementation Plan

## Overview

Rencana implementasi website SMK Muda Bawean menggunakan Laravel 11 Native. Terdapat 25 task yang mencakup setup proyek, database, services, models, middleware, halaman publik, admin panel, testing properti, dan audit aksesibilitas/SEO. Implementasi dilakukan secara bertahap mengikuti urutan dependency.

## Task Dependency Graph

```
1 (Setup)
└── 2 (Migrations & Seeders)
    ├── 3 (Core Services)        ← depends: 1, 2
    └── 4 (Models & Observers)  ← depends: 2, 3
        └── 5 (Middleware)       ← depends: 2, 4
            └── 6 (Layouts)      ← depends: 3, 4, 5
                ├── 7  (Home)          ← depends: 3, 4, 6
                ├── 8  (Profil)        ← depends: 3, 4, 6
                ├── 9  (Berita)        ← depends: 3, 4, 6
                ├── 10 (Pengumuman)    ← depends: 3, 4, 6
                ├── 11 (Kelulusan)     ← depends: 2, 6
                ├── 12 (Alumni)        ← depends: 2, 4, 6
                ├── 13 (PPDB)          ← depends: 2, 3, 4, 6
                ├── 14 (Sitemap)       ← depends: 3, 4
                ├── 15 (Admin Auth)    ← depends: 4, 5, 6
                ├── 16 (Admin Berita)  ← depends: 3, 4, 5, 6
                ├── 17 (Admin Pengumuman) ← depends: 3, 4, 5, 6
                ├── 18 (Admin Kelulusan)  ← depends: 3, 4, 5, 6
                ├── 19 (Admin Alumni)     ← depends: 4, 5, 6
                ├── 20 (Admin PPDB)       ← depends: 4, 5, 6
                ├── 21 (Admin Pendidik/Halaman/FAQ) ← depends: 3, 4, 5, 6
                ├── 22 (Admin Settings)   ← depends: 3, 4, 5, 6
                ├── 23 (Admin Users)      ← depends: 4, 5, 6
                ├── 24 (Property Tests)   ← depends: 3, 4, 7, 9, 11, 12, 13, 16, 17, 18
                └── 25 (SEO/A11y Audit)   ← depends: 6, 7, 8, 9, 10, 11, 12, 13, 14, 22
```

## Notes

- Semua file upload disimpan di `storage/app/private/uploads/` (di luar direktori `public/`) dengan nama UUID
- Cache menggunakan `file` driver — tidak tersedia Redis/Memcached di shared hosting
- Queue menggunakan `sync` driver — tidak ada daemon/supervisor
- TailwindCSS via CDN Play CDN — tidak perlu `npm install` untuk development
- HTMLPurifier membutuhkan direktori `storage/app/htmlpurifier/` dengan permission write
- Setiap task yang mengandung test wajib dijalankan dengan `php artisan test` sebelum melanjutkan ke task berikutnya

- [x] 1. Setup Project Laravel & Konfigurasi Dasar
  - Pastikan project Laravel 11 sudah ada di `c:\laragon\www\smkmudabawean\` atau buat baru dengan `composer create-project laravel/laravel .`
  - Konfigurasi `.env`: set `DB_CONNECTION=mysql`, `DB_DATABASE=smkmudabawean`, `CACHE_STORE=file`, `QUEUE_CONNECTION=sync`, `SESSION_DRIVER=file`, `SESSION_LIFETIME=60`, `APP_URL`, `APP_NAME="SMK Muda Bawean"`
  - Install dependency wajib: `composer require ezyang/htmlpurifier:^4.17`
  - Buat file `routes/admin.php` dan daftarkan di `bootstrap/app.php` menggunakan `->withRouting(then: ...)` dengan middleware `web`
  - Konfigurasi TailwindCSS via CDN Play CDN di layout (tidak perlu `npm install`)
  - Jalankan `php artisan storage:link` untuk membuat symlink `public/storage`
  - Buat direktori `storage/app/htmlpurifier/` untuk cache HTMLPurifier
  - Buat direktori `storage/app/private/uploads/` untuk file upload (di luar public)
  - Tambahkan `.htaccess` dengan konfigurasi gzip/brotli dan cache headers untuk aset statis
  - **Requirements**: 11, 12
  - **Dependencies**: none

- [x] 2. Database Migrations & Seeders
  - Buat migration `0001_01_01_000000_create_users_table.php` dengan kolom: `id`, `name`, `email` (unique), `password`, `role` enum('admin','editor') default 'editor', `login_attempts` tinyint default 0, `locked_until` timestamp nullable, `remember_token`, `timestamps`
  - Buat migration `2025_01_01_000001_create_settings_table.php` dengan kolom: `id`, `key` varchar(100) unique, `value` text nullable, `timestamps`
  - Buat migration `2025_01_01_000002_create_pages_table.php` dengan kolom: `id`, `slug` unique, `title`, `content` longtext nullable, `meta_title` varchar(60) nullable, `meta_description` varchar(160) nullable, `timestamps`
  - Buat migration `2025_01_01_000003_create_teachers_table.php` dengan kolom: `id`, `name`, `position`, `photo` nullable, `order` smallint default 0, `timestamps`, index pada `order`
  - Buat migration `2025_01_01_000004_create_posts_table.php` dengan kolom lengkap + FK `author_id` ke `users.id` (restrictOnDelete), `softDeletes()`, index pada `status`, `published_at`, composite `(status, published_at)`
  - Buat migration `2025_01_01_000005_create_announcements_table.php` dengan kolom + index pada `status`, `published_at`, composite `(status, published_at)`
  - Buat migration `2025_01_01_000006_create_graduations_table.php` dengan kolom + index pada `academic_year`, `student_name`, `exam_number`
  - Buat migration `2025_01_01_000007_create_alumni_table.php` dengan kolom + unique `email`, index pada `graduation_year`
  - Buat migration `2025_01_01_000008_create_tracer_studies_table.php` dengan FK `alumni_id` nullable ke `alumni.id` (ON DELETE SET NULL), index pada `alumni_id`, `graduation_year`
  - Buat migration `2025_01_01_000009_create_ppdb_registrations_table.php` dengan kolom + unique `registration_number`, index pada `status`, `created_at`
  - Buat migration `2025_01_01_000010_create_faqs_table.php` dengan kolom + index pada `order`, `is_active`
  - Buat `AdminUserSeeder` yang membuat user admin default dengan `firstOrCreate` (email: `admin@smkmudabawean.sch.id`, role: admin)
  - Buat `SettingSeeder` yang mengisi 17 setting default dengan `firstOrCreate`
  - Buat `PageSeeder` yang seed dua halaman statis: slug `sejarah` dan `visi-misi`
  - Update `DatabaseSeeder` untuk memanggil ketiga seeder di atas
  - Jalankan `php artisan migrate --seed` dan pastikan tidak ada error
  - **Requirements**: 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12
  - **Dependencies**: 1

- [x] 3. Core Services & Helpers
  - Buat `app/Services/SlugService.php` dengan method `generate(string $title, string $modelClass, ?int $excludeId = null): string` dan private `slugify()` (lowercase, replace non-alphanumeric dengan `-`, trim `-`) serta `exists()`
  - Buat `app/Services/HtmlSanitizerService.php` sebagai wrapper `ezyang/htmlpurifier` — konfigurasi tag diizinkan, hapus script/iframe/object/embed dan semua event handler attributes, cache purifier di `storage/app/htmlpurifier/`
  - Buat `app/Services/ThemeService.php` dengan method `getColors(): array` yang membaca 3 warna dari `Cache::remember('theme_colors', 3600, ...)`
  - Buat `app/Services/CacheService.php` dengan method `remember()`, `rememberStale()` (dual-key stale-while-revalidate dengan grace period 300 detik, dispatch afterResponse), `forget()`, `forgetByKeys()`
  - Buat `app/Services/SchemaMarkupService.php` dengan method: `educationalOrganization()`, `newsArticle()`, `announcement()`, `localBusiness()`, `ppdbEvent()`, `faqPage()` — semua return null jika field wajib tidak lengkap
  - Buat `app/Services/SitemapService.php` dengan method `generate(): string` yang query post/announcement published, cache 60 detik di key `sitemap_xml`, dan build XML valid sesuai spesifikasi sitemap.org
  - Buat `app/Services/CsvService.php` dengan method `importGraduations(UploadedFile $file, string $academicYear): array` (validasi header, partial import, laporan ringkasan) dan `exportGraduations(string $academicYear): string`
  - Buat `app/Helpers/helpers.php` dengan fungsi global: `seo_meta()`, `schema_json_ld()`, `theme_colors()`
  - Daftarkan helpers di `composer.json` bagian `autoload.files`, jalankan `composer dump-autoload`
  - Buat/update `app/Providers/AppServiceProvider.php` — register semua services sebagai singleton, daftarkan observers, setup view composer untuk `layouts.public` (inject `themeColors` dan `schoolName`)
  - **Requirements**: 9, 10, 11, 14
  - **Dependencies**: 1, 2

- [x] 4. Models & Observers
  - Buat `app/Models/User.php` dengan `$fillable`, `$casts` (password hidden), relasi `posts()` hasMany
  - Buat `app/Models/Post.php` dengan `SoftDeletes`, `$fillable`, `$casts` (published_at datetime), scope `scopePublished()`, relasi `author()` belongsTo User
  - Buat `app/Models/Announcement.php` dengan `$fillable`, `$casts`, scope `scopePublished()`
  - Buat `app/Models/Graduation.php` dengan `$fillable`
  - Buat `app/Models/Alumni.php` dengan `$fillable`, relasi `tracerStudies()` hasMany
  - Buat `app/Models/TracerStudy.php` dengan `$fillable`, relasi `alumni()` belongsTo Alumni (nullable)
  - Buat `app/Models/PpdbRegistration.php` dengan `$fillable`, `$casts` (birth_date date)
  - Buat `app/Models/Teacher.php` dengan `$fillable`, scope `scopeOrdered()`
  - Buat `app/Models/Setting.php` dengan method static `get(string $key, mixed $default = null)` dan `set(string $key, mixed $value)` menggunakan cache `settings_all`
  - Buat `app/Models/Page.php` dengan `$fillable`
  - Buat `app/Models/Faq.php` dengan `$fillable`, scope `scopeActive()`, scope `scopeOrdered()`
  - Buat `app/Observers/PostObserver.php` — method `saved()` dan `deleted()` yang invalidasi cache: `home_page`, `post_{slug}`, `sitemap_xml`
  - Buat `app/Observers/AnnouncementObserver.php` — invalidasi `home_page`, `announcement_{slug}`, `sitemap_xml`
  - Buat `app/Observers/PageObserver.php` — invalidasi `page_{slug}`, `profil_{slug}`
  - Buat `app/Observers/TeacherObserver.php` — invalidasi `profil_pendidik`
  - Buat `app/Observers/SettingObserver.php` — invalidasi `settings_all`, `theme_colors`
  - Daftarkan semua observer di `AppServiceProvider::boot()` menggunakan `Model::observe(Observer::class)`
  - **Requirements**: 7, 10, 11
  - **Dependencies**: 2, 3

- [x] 5. Middleware & Authentication
  - Buat `app/Http/Middleware/AdminAuth.php` — cek `Auth::check()`, jika tidak login redirect ke `route('admin.login')` dengan `intended()`
  - Buat `app/Http/Middleware/RoleCheck.php` — terima parameter role di constructor, jika `auth()->user()->role !== $role` maka `abort(403)`
  - Buat `app/Http/Middleware/RateLimitLogin.php` — throttle per `sha1($request->ip() . '|' . $request->input('email'))`, batas 10 request/menit, return 429 jika exceeded
  - Buat `app/Http/Controllers/Auth/LoginController.php` dengan method `showLoginForm()`, `login()` (cek `locked_until`, Auth::attempt, increment `login_attempts`, lock setelah 5 gagal selama 15 menit, reset pada sukses), `logout()`
  - Register alias middleware di `bootstrap/app.php`: `admin.auth` → `AdminAuth`, `role` → `RoleCheck`
  - Pastikan middleware `RateLimitLogin` diterapkan pada route `admin.login.submit` via `throttle:10,1`
  - Tulis test di `tests/Feature/Admin/AuthTest.php`: login sukses → redirect dashboard, 5 kali gagal → akun terkunci, akun terkunci → tampilkan pesan waktu buka, logout → sesi diinvalidasi, unauthenticated → redirect login
  - **Requirements**: 8, 12
  - **Dependencies**: 2, 4

- [x] 6. Blade Layouts & Components
  - Buat `resources/views/layouts/public.blade.php` — struktur HTML semantik (`<html lang="id">`, `<header role="banner">`, `<nav role="navigation" aria-label="Navigasi utama">`, `<main id="main-content" role="main" tabindex="-1">`, `<footer role="contentinfo">`), inline CSS vars tema, TailwindCSS CDN, hamburger menu dengan vanilla JS (toggle `aria-expanded` dan `hidden` class), skip-to-content link `<a href="#main-content" class="sr-only focus:not-sr-only">`
  - Buat `resources/views/layouts/admin.blade.php` — sidebar role-based (menu Pengguna & Pengaturan hanya tampil untuk role `admin`), breadcrumb, flash messages `session('success')` dan `session('error')` dengan `role="alert"`, CSRF meta tag di `<head>`
  - Buat `resources/views/components/seo-head.blade.php` — render `<title>`, `<meta name="description">`, `<link rel="canonical">`, Open Graph tags (og:title, og:description, og:image conditional, og:url, og:type)
  - Buat `resources/views/components/schema-markup.blade.php` — render `<script type="application/ld+json">` hanya jika `$schema` tidak null, gunakan `JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT`
  - Buat `resources/views/components/news-card.blade.php` — thumbnail dengan `loading="lazy"` dan alt text dari judul post, judul, excerpt, tanggal, link ke detail
  - Buat `resources/views/components/announcement-item.blade.php` — judul, tanggal, badge lampiran jika ada
  - Buat `resources/views/components/pagination.blade.php` — wrapper `$paginator->links()` dengan aria-label aksesibel pada tombol halaman
  - Buat `resources/views/components/breadcrumb.blade.php` — `<nav aria-label="Breadcrumb">` dengan `<ol>` dan schema BreadcrumbList JSON-LD, terima array `$breadcrumbs`
  - Buat `resources/views/errors/404.blade.php` — extends `layouts.public`, pesan ramah, link kembali ke home
  - Buat `resources/views/errors/410.blade.php` — extends `layouts.public`, pesan konten telah dihapus
  - Buat `resources/views/errors/403.blade.php` — extends `layouts.public`, pesan akses ditolak
  - **Requirements**: 9, 10, 13
  - **Dependencies**: 3, 4, 5

- [x] 7. Halaman Home (Public)
  - Buat `app/Http/Controllers/Public/HomeController.php` dengan method `index()` yang menggunakan `CacheService::rememberStale('home_page', 3600, ...)` untuk mengambil 6 post published terbaru (with eager load author), 5 announcement published terbaru, dan setting `principal_greeting`
  - Buat view `resources/views/public/home/index.blade.php` — extends `layouts.public`, Hero Section, section berita (gunakan komponen `news-card`), section pengumuman (gunakan `announcement-item`), section sambutan kepala sekolah
  - Implementasi empty state: tampilkan pesan "Belum ada berita yang dipublikasikan" jika `$posts->isEmpty()`, pesan serupa untuk pengumuman kosong — tetap render seluruh komponen halaman
  - Pastikan Home Page menggunakan lazy loading pada thumbnail berita (gambar hero section gunakan `loading="eager"`)
  - Pastikan SEO: inject `$seo` array ke view dengan title dan description default halaman home
  - Tulis test di `tests/Feature/Public/HomePageTest.php`: response 200, menampilkan tepat 6 post (bukan 7+), menampilkan tepat 5 pengumuman, empty state tampil saat tidak ada konten, cache digunakan (tidak query ulang DB), format heading hierarchy benar
  - **Requirements**: 1, 9, 11, 13
  - **Dependencies**: 3, 4, 6

- [x] 8. Halaman Profil Sekolah (Public)
  - Buat `app/Http/Controllers/Public/ProfileController.php` dengan method `sejarah()`, `visiMisi()`, `pendidik()` — masing-masing menggunakan `CacheService::remember()` dengan key `profil_sejarah`, `profil_visi_misi`, `profil_pendidik` dan TTL 3600
  - Buat view `resources/views/public/profil/sejarah.blade.php` — render konten HTML dari `pages.sejarah`, sematkan Schema EducationalOrganization jika data lengkap
  - Buat view `resources/views/public/profil/visi-misi.blade.php` — render konten HTML dari `pages.visi-misi`, sematkan Schema EducationalOrganization
  - Buat view `resources/views/public/profil/pendidik.blade.php` — render daftar guru dengan foto (lazy loading), nama, jabatan; tampilkan placeholder dengan alt deskriptif jika foto tidak ada
  - Tambahkan catch-all route `/profil/{any}` yang memanggil `abort(404)` di `routes/web.php` setelah ketiga route spesifik
  - Inject Schema EducationalOrganization dari `SchemaMarkupService::educationalOrganization()` ke view sejarah dan visi-misi
  - Tulis test di `tests/Feature/Public/ProfilePageTest.php`: semua 3 sub-halaman response 200, path tidak valid (misal `/profil/kontak`) response 404, foto placeholder muncul jika null, Schema ada di halaman sejarah dan visi-misi
  - **Requirements**: 2, 9, 13
  - **Dependencies**: 3, 4, 6

- [x] 9. Halaman Berita (Public)
  - Buat `app/Http/Controllers/Public/NewsController.php` dengan method `index()` (eager load author, paginate 10, diurutkan `published_at` desc) dan `show(string $slug)` (cek soft-deleted → 410, tidak ada → 404, ada → tampilkan)
  - Buat view `resources/views/public/berita/index.blade.php` — extends `layouts.public`, daftar berita menggunakan komponen `news-card`, komponen `pagination`, breadcrumb
  - Buat view `resources/views/public/berita/show.blade.php` — extends `layouts.public`, render konten HTML post, thumbnail (eager load), info penulis dan tanggal, breadcrumb, Schema NewsArticle
  - Implementasi 404 untuk slug tidak ditemukan dan 410 untuk post soft-deleted di `show()` — cek `Post::onlyTrashed()->where('slug', $slug)->exists()` sebelum query normal
  - Inject `$seo` ke view: gunakan `meta_title` jika ada, fallback ke `title`; gunakan `meta_description` jika ada, fallback ke 160 karakter pertama konten tanpa HTML
  - Inject Schema NewsArticle dari `SchemaMarkupService::newsArticle($post)` ke view show
  - Tulis test di `tests/Feature/Public/NewsPageTest.php`: listing response 200, pagination correct, detail response 200, slug tidak ada → 404, slug soft-deleted → 410, Schema ada di halaman detail
  - **Requirements**: 7, 9, 11, 13
  - **Dependencies**: 3, 4, 6

- [x] 10. Halaman Pengumuman (Public)
  - Buat `app/Http/Controllers/Public/AnnouncementController.php` dengan method `index()` (paginate 10), `show(string $slug)` (404 jika tidak ada), `download(string $slug)` (404 jika tidak ada lampiran/file, stream download dengan `response()->download()`)
  - Buat view `resources/views/public/pengumuman/index.blade.php` — daftar pengumuman dengan komponen `announcement-item`, komponen `pagination`, breadcrumb
  - Buat view `resources/views/public/pengumuman/show.blade.php` — konten lengkap, tanggal, tautan unduh lampiran (tampilkan nama file + ukuran file), sembunyikan bagian lampiran jika tidak ada; Schema Announcement (Article sebagai fallback)
  - Implementasi download: verifikasi file ada di `storage/app/private/uploads/`, gunakan `response()->download($path)` — tidak ada akses langsung ke file
  - Inject `$seo` dan Schema ke view menggunakan `SchemaMarkupService::announcement($ann)`
  - Tulis test di `tests/Feature/Public/AnnouncementPageTest.php`: listing 200, pagination, detail 200, download sukses, download 404 jika tidak ada lampiran, slug tidak ada → 404
  - **Requirements**: 3, 9, 12, 13
  - **Dependencies**: 3, 4, 6

- [x] 11. Halaman Kelulusan (Public)
  - Buat `app/Http/Controllers/Public/GraduationController.php` dengan method `index(Request $request)` — ambil daftar tahun ajaran unik dari DB, filter per `academic_year`, pencarian case-insensitive per `student_name` atau `exam_number` via query string `?search=` dan `?year=`
  - Buat view `resources/views/public/kelulusan/index.blade.php` — dropdown pilih tahun ajaran, form pencarian dengan input text, tabel hasil (nama siswa, nomor peserta, program keahlian, status LULUS/TIDAK LULUS), breadcrumb
  - Implementasi empty state: pesan "Data kelulusan untuk tahun ajaran ini belum tersedia" jika hasil kosong; pesan "Tidak ditemukan data yang cocok" jika pencarian tidak menemukan hasil
  - Pastikan halaman TIDAK menampilkan NIK, tanggal lahir, atau data pribadi sensitif
  - Tulis test di `tests/Feature/Public/GraduationPageTest.php`: listing response 200, filter tahun bekerja, pencarian case-insensitive, empty state per tahun, data sensitif tidak tampil
  - **Requirements**: 4, 13
  - **Dependencies**: 2, 6

- [x] 12. Halaman Alumni & Tracer Study (Public)
  - Buat `app/Http/Controllers/Public/AlumniController.php` dengan method `index()`, `store()`, `tracerStudy()`, `storeTracerStudy()`
  - Buat `app/Http/Requests/StoreAlumniRequest.php` dengan rules: `full_name` required max:100, `graduation_year` integer min:1990 max:tahun_sekarang, `email` email:rfc unique:alumni,email, `phone` nullable, `address` nullable
  - Buat `app/Http/Requests/StoreTracerStudyRequest.php` dengan rules: `full_name` required max:100, `graduation_year` required integer, `education_status` required max:100, `employment_status` required max:100, `employer_name` nullable, `position` nullable
  - Buat view `resources/views/public/alumni/index.blade.php` — formulir pendaftaran alumni dengan label terhubung (`<label for="...">`), pesan validasi per-kolom dengan `role="alert"`, nilai kolom dipertahankan via `old()`
  - Buat view `resources/views/public/alumni/tracer-study.blade.php` — formulir tracer study dengan validasi serupa
  - Implementasi pesan konfirmasi setelah submit sukses via `session('success')`
  - Pastikan tidak ada endpoint publik yang mengembalikan data alumni individual
  - Tulis test di `tests/Feature/Public/AlumniPageTest.php`: submit valid → 302 + flash success, submit invalid → 422 + pesan per-kolom, email duplikat → error pada kolom email, tracer study submit valid
  - **Requirements**: 5, 12, 13
  - **Dependencies**: 2, 4, 6

- [x] 13. Halaman Pendaftaran PPDB (Public)
  - Buat `app/Http/Controllers/Public/PpdbController.php` dengan method `index()` (cek `Setting::get('ppdb_is_active')`, inject setting tanggal mulai/selesai, inject Schema Event) dan `store(StorePpdbRequest $request)` (generate nomor registrasi via `DB::transaction` + `lockForUpdate`, simpan, kembalikan nomor registrasi di flash message)
  - Buat `app/Http/Requests/StorePpdbRequest.php` dengan rules: `full_name` required max:100, `birth_place` required max:50, `birth_date` required date_format:Y-m-d before:today, `school_origin` required max:100, `parent_name` required max:100, `phone` required regex:/^[0-9]{10,13}$/
  - Implementasi nomor registrasi format `PPDB-{YYYYMMDD}-{0001}` dengan pessimistic lock (`lockForUpdate()`) di dalam `DB::transaction` untuk mencegah race condition
  - Buat view `resources/views/public/ppdb/index.blade.php` — tampilkan formulir HANYA jika `ppdb_is_active == 1`, tampilkan pesan "Pendaftaran belum/sudah dibuka" jika tidak aktif; formulir dengan validasi per-kolom, nilai dipertahankan via `old()`
  - Inject Schema Event dari `SchemaMarkupService::ppdbEvent($settings)` — hanya dirender jika `ppdb_start_date` dan `ppdb_end_date` tidak kosong
  - Tulis test di `tests/Feature/Public/PpdbPageTest.php`: formulir tampil jika aktif, formulir tidak tampil jika tidak aktif, submit valid → nomor registrasi unik di response, submit invalid → pesan per-kolom, race condition → nomor tetap unik
  - **Requirements**: 6, 9, 12, 13
  - **Dependencies**: 2, 3, 4, 6

- [x] 14. Sitemap & Robots.txt
  - Buat `app/Http/Controllers/Public/SitemapController.php` dengan method `index()` (return XML dari `SitemapService::generate()` dengan Content-Type `application/xml`) dan `robots()` (return teks dari `Setting::get('robots_txt')` dengan Content-Type `text/plain`)
  - Pastikan `SitemapService` sudah diimplementasi di Task 3 dengan cache 60 detik di key `sitemap_xml`
  - Daftarkan kedua route di `routes/web.php`: `GET /sitemap.xml` dan `GET /robots.txt`
  - Tulis test di `tests/Feature/Public/SitemapTest.php`: sitemap.xml response 200 dengan Content-Type XML, output XML valid (bisa di-parse), robots.txt response 200 dengan Content-Type text/plain, sitemap diperbarui dalam 60 detik setelah konten baru dipublikasi
  - **Requirements**: 9
  - **Dependencies**: 3, 4

- [x] 15. Admin Panel — Auth & Dashboard
  - Pastikan `LoginController` dari Task 5 sudah lengkap dengan `showLoginForm()`, `login()`, `logout()`
  - Buat view `resources/views/admin/auth/login.blade.php` — form login dengan CSRF token, input email + password, pesan error, tidak ada placeholder yang bocorkan info kredensial default
  - Buat `app/Http/Controllers/Admin/DashboardController.php` dengan method `index()` yang mengambil 4 statistik: `Post::count()`, `Announcement::count()`, `PpdbRegistration::count()`, `Alumni::count()`
  - Buat view `resources/views/admin/dashboard/index.blade.php` — extends `layouts.admin`, tampilkan 4 kartu statistik, breadcrumb "Dashboard"
  - Pastikan redirect setelah login berdasarkan role: admin → dashboard, editor → dashboard (dengan menu sesuai hak akses)
  - Tulis test di `tests/Feature/Admin/AuthTest.php`: login sukses → redirect dashboard, login gagal → kembali ke form dengan error, 5 kali gagal → akun terkunci 15 menit, logout → sesi diinvalidasi + redirect login, unauthenticated → redirect login, sesi expired 60 menit → redirect login
  - **Requirements**: 8
  - **Dependencies**: 4, 5, 6

- [x] 16. Admin Panel — Manajemen Berita
  - Buat `app/Http/Controllers/Admin/PostController.php` dengan method CRUD: `index()` (paginate 15, filter status), `create()`, `store(StorePostRequest $r)`, `edit(Post $post)`, `update(UpdatePostRequest $r, Post $post)`, `destroy(Post $post)` (soft delete)
  - Buat `app/Http/Requests/StorePostRequest.php` dan `UpdatePostRequest.php` dengan rules: title required max:255, content required, status enum, thumbnail nullable file mimes:jpg,jpeg,png,webp max:2048, meta_title nullable max:60, meta_description nullable max:160, published_at nullable date
  - Implementasi upload thumbnail: simpan di `storage/app/private/uploads/` dengan nama `Str::uuid() . '.' . $ext`, hapus file lama saat update/delete
  - Implementasi auto-generate slug via `SlugService::generate($title, Post::class, $excludeId)` saat create dan saat title berubah di update
  - Implementasi sanitasi konten via `HtmlSanitizerService::clean($content)` sebelum simpan ke DB
  - Invalidasi cache via Observer (sudah terdaftar di Task 4) — tidak perlu manual di controller
  - Buat view `resources/views/admin/posts/index.blade.php` — tabel berita dengan filter status, tombol aksi
  - Buat view `resources/views/admin/posts/create.blade.php` dan `edit.blade.php` — form dengan textarea rich text (placeholder), input thumbnail, input meta fields, select status, input published_at
  - Tulis test di `tests/Feature/Admin/PostCrudTest.php`: CRUD lengkap, slug auto-generate, thumbnail upload valid/invalid, konten disanitasi, soft-delete → 410 di public
  - **Requirements**: 7, 9, 11, 12
  - **Dependencies**: 3, 4, 5, 6

- [x] 17. Admin Panel — Manajemen Pengumuman
  - Buat `app/Http/Controllers/Admin/AnnouncementController.php` dengan method CRUD lengkap: `index()`, `create()`, `store()`, `edit()`, `update()`, `destroy()`
  - Buat `app/Http/Requests/StoreAnnouncementRequest.php` dengan rules: title required max:255, content nullable, status enum, attachment nullable file mimes:pdf max:2048, meta_title nullable max:60, meta_description nullable max:160, published_at nullable date
  - Implementasi upload lampiran PDF: simpan di `storage/app/private/uploads/` dengan nama UUID, hapus file lama saat update/delete
  - Implementasi auto-generate slug via `SlugService` dan sanitasi konten via `HtmlSanitizerService`
  - Cache diinvalidasi otomatis via `AnnouncementObserver` (sudah terdaftar di Task 4)
  - Buat view `resources/views/admin/announcements/index.blade.php`, `create.blade.php`, `edit.blade.php`
  - Tulis test di `tests/Feature/Admin/AnnouncementCrudTest.php`: CRUD lengkap, upload PDF valid, upload file bukan PDF → rejected, cache invalidasi setelah save
  - **Requirements**: 3, 9, 11, 12
  - **Dependencies**: 3, 4, 5, 6

- [x] 18. Admin Panel — Manajemen Kelulusan
  - Buat `app/Http/Controllers/Admin/GraduationController.php` dengan method: `index()` (list per tahun ajaran), `create()` (form upload CSV), `import(Request $r)` (panggil `CsvService::importGraduations()`), `export(Request $r)` (panggil `CsvService::exportGraduations()`, return file download)
  - Pastikan `CsvService::importGraduations()` dari Task 3 sudah validasi 4 header wajib, lakukan partial import (skip baris invalid), kembalikan array `['imported', 'failed', 'errors']`
  - Tampilkan laporan ringkasan setelah import: "Berhasil mengimpor X baris, Y baris gagal" beserta daftar error per baris jika ada
  - Jika header wajib tidak ada, tolak seluruh file dan tampilkan pesan error deskriptif menyebutkan kolom yang hilang
  - Buat view `resources/views/admin/graduations/index.blade.php` — tabel data kelulusan (filter per tahun ajaran), tombol import, tombol export
  - Buat view `resources/views/admin/graduations/create.blade.php` — form upload CSV dengan input file, dropdown tahun ajaran
  - Tulis test di `tests/Feature/Admin/GraduationImportTest.php`: import CSV valid → semua baris tersimpan, CSV header salah → ditolak + pesan kolom hilang, partial import → baris valid tersimpan + ringkasan, export → file CSV dapat didownload
  - **Requirements**: 4, 12
  - **Dependencies**: 3, 4, 5, 6

- [x] 19. Admin Panel — Manajemen Alumni & Tracer Study
  - Buat `app/Http/Controllers/Admin/AlumniController.php` dengan method: `index()` (list alumni + pagination, read-only), `show(Alumni $alumni)` (detail individual, hanya admin), `tracerStudies()` (statistik aggregasi)
  - Implementasi statistik tracer study di `tracerStudies()`: jumlah total responden, persentase yang melanjutkan pendidikan (`education_status = 'Melanjutkan'`), persentase yang bekerja (`employment_status = 'Bekerja'`)
  - Buat view `resources/views/admin/alumni/index.blade.php` — tabel alumni (nama, tahun lulus, email), breadcrumb
  - Buat view `resources/views/admin/alumni/show.blade.php` — detail alumni individual, hanya tampil untuk role admin
  - Buat view `resources/views/admin/alumni/tracer-studies.blade.php` — kartu statistik: total responden, % melanjutkan, % bekerja
  - Pastikan akses data detail individual dilindungi dengan middleware `role:admin`
  - Tulis test di `tests/Feature/Admin/AlumniManagementTest.php`: list alumni 200, statistik tracer study benar, editor tidak dapat akses show detail (403), data alumni tidak dapat diakses tanpa auth
  - **Requirements**: 5, 8, 12
  - **Dependencies**: 4, 5, 6

- [x] 20. Admin Panel — Manajemen PPDB
  - Buat `app/Http/Controllers/Admin/PpdbController.php` dengan method: `index(Request $r)` (list dengan filter status), `show(PpdbRegistration $ppdb)`, `update(Request $r, PpdbRegistration $ppdb)` (ubah status: menunggu/diterima/ditolak), `export()` (export CSV semua pendaftar atau yang difilter)
  - Implementasi filter status via query string `?status=menunggu|diterima|ditolak` di `index()`
  - Implementasi export CSV dengan header: registration_number, full_name, birth_place, birth_date, school_origin, parent_name, phone, status, created_at
  - Buat view `resources/views/admin/ppdb/index.blade.php` — tabel pendaftar dengan filter status (tab/dropdown), tombol export CSV, kolom status dapat diubah via form
  - Buat view `resources/views/admin/ppdb/show.blade.php` — detail pendaftar, form ubah status
  - Tulis test di `tests/Feature/Admin/PpdbManagementTest.php`: list semua pendaftar, filter status bekerja, update status sukses, export CSV dapat didownload
  - **Requirements**: 6, 8, 12
  - **Dependencies**: 4, 5, 6

- [x] 21. Admin Panel — Pendidik, Halaman Statis, FAQ
  - Buat `app/Http/Controllers/Admin/TeacherController.php` dengan CRUD lengkap; upload foto guru di `storage/app/private/uploads/` dengan UUID, validasi mimes:jpg,jpeg,png,webp max:2048; buat `StoreTeacherRequest.php`
  - Buat view `resources/views/admin/teachers/index.blade.php`, `create.blade.php`, `edit.blade.php` — form dengan input nama, jabatan, upload foto, input order
  - Buat `app/Http/Controllers/Admin/PageController.php` dengan method `index()`, `edit(Page $page)`, `update(StorePageRequest $r, Page $page)` — hanya edit konten halaman statis (sejarah, visi-misi), sanitasi HTML via `HtmlSanitizerService`, buat `StorePageRequest.php`
  - Buat view `resources/views/admin/pages/index.blade.php` — daftar halaman statis, `edit.blade.php` — form edit dengan textarea rich text, meta fields
  - Buat `app/Http/Controllers/Admin/FaqController.php` dengan CRUD + ordering; buat `StoreFaqRequest.php` dengan rules: question required, answer required, order integer, is_active boolean
  - Buat view `resources/views/admin/faqs/index.blade.php` — tabel FAQ dengan kolom order (bisa drag atau input manual), toggle aktif/nonaktif, `create.blade.php`, `edit.blade.php`
  - Pastikan `TeacherObserver` dan `PageObserver` menginvalidasi cache yang relevan (sudah di Task 4)
  - **Requirements**: 2, 9, 11, 12
  - **Dependencies**: 3, 4, 5, 6

- [x] 22. Admin Panel — Pengaturan (Tema, SEO, Sekolah)
  - Buat `app/Http/Controllers/Admin/SettingController.php` dengan method: `index()` + `update()` (info sekolah), `seo()` + `updateSeo()` (meta global, robots.txt), `theme()` + `updateTheme()` (warna tema) — semua gunakan `Setting::set()` yang otomatis invalidasi cache
  - Buat `app/Http/Requests/StoreSettingRequest.php` dengan rules validasi: `color_primary`, `color_secondary`, `color_accent` wajib regex `/^#[0-9A-Fa-f]{6}$/`, field info sekolah nullable string, robots_txt nullable string
  - Buat view `resources/views/admin/settings/index.blade.php` — form info sekolah (nama, alamat, telepon, email, koordinat), form media sosial
  - Buat view `resources/views/admin/settings/seo.blade.php` — textarea robots.txt
  - Buat view `resources/views/admin/settings/theme.blade.php` — color picker dengan `data-color-preview="--color-primary"` dll., live preview via vanilla JS (`input` event → `document.documentElement.style.setProperty()`), preview komponen representatif (header, tombol primer)
  - Implementasi LocalBusiness schema kondisional: tampilkan di semua halaman publik hanya jika `school_name`, `school_address`, `school_phone`, `school_geo_lat`, `school_geo_lng` semua terisi
  - Tulis test di `tests/Feature/Admin/SettingsTest.php`: update tema valid → disimpan dan cache diinvalidasi, warna invalid (#RRGGBB salah) → ditolak, robots.txt diperbarui → tampil di `/robots.txt`, hanya admin yang bisa akses settings (editor → 403)
  - **Requirements**: 9, 10, 11, 12
  - **Dependencies**: 3, 4, 5, 6

- [x] 23. Admin Panel — Manajemen Pengguna
  - Buat `app/Http/Controllers/Admin/UserController.php` dengan CRUD lengkap; dilindungi middleware `role:admin` (sudah terdaftar di route); buat `StoreUserRequest.php` dengan rules: name required max:100, email required email unique:users, password required min:8 confirmed, role required in:admin,editor
  - Buat view `resources/views/admin/users/index.blade.php` — tabel pengguna (nama, email, role, status), tombol tambah, edit, hapus
  - Buat view `resources/views/admin/users/create.blade.php` dan `edit.blade.php` — form dengan input nama, email, password (create wajib, edit opsional), select role
  - Pastikan tidak bisa menghapus diri sendiri (`destroy()` → abort(403) jika `$user->id === auth()->id()`)
  - Pastikan semua route `/admin/users/*` hanya bisa diakses oleh role admin (editor → 403)
  - **Requirements**: 8, 12
  - **Dependencies**: 4, 5, 6

- [x] 24. Property-Based Tests (P1–P10)
  - Buat `tests/Feature/Property/SlugPropertyTest.php` — P1 (idempotence: `generate(title) == generate(generate(title))`), P2 (karakter valid: output hanya `[a-z0-9-]`, tidak diawali/diakhiri `-`) — jalankan 100+ iterasi dengan judul random
  - Buat `tests/Feature/Property/HtmlSanitizerPropertyTest.php` — P3 (safety: input dengan `<script>`, `<iframe>`, `<object>`, `<embed>`, event handlers → output tidak mengandung string berbahaya tersebut), P4 (preservation: input hanya dengan tag diizinkan → output mempertahankan teks dan struktur)
  - Buat `tests/Feature/Property/PpdbRegistrationPropertyTest.php` — P5 (uniqueness: N pendaftaran berturut-turut menghasilkan N nomor registrasi unik — simulasi 20 pendaftaran berurutan)
  - Buat `tests/Feature/Property/SchemaMarkupPropertyTest.php` — P6 (valid JSON: `json_decode(json_encode($schema)) === $schema` untuk semua schema yang dihasilkan `SchemaMarkupService`)
  - Buat `tests/Feature/Property/ThemeColorPropertyTest.php` — P7 (CSS variable round-trip: nilai warna yang disimpan via `Setting::set()` identik dengan yang dikembalikan `ThemeService::getColors()`)
  - Buat `tests/Feature/Property/CsvRoundTripPropertyTest.php` — P8 (CSV round-trip: import CSV → export CSV → nilai per kolom identik dengan file asal, termasuk karakter khusus dan spasi)
  - Buat `tests/Feature/Property/PaginationPropertyTest.php` — P9 (pagination completeness: sum item di semua halaman = N total item, tidak ada ID duplikat di halaman berbeda)
  - Buat `tests/Feature/Property/CacheInvalidationPropertyTest.php` — P10 (cache consistency: setelah update konten + cache invalidasi Observer, request berikutnya mengembalikan konten terbaru, elapsed time ≤ 5 detik)
  - Pastikan semua test dapat dijalankan dengan `php artisan test --filter=PropertyTest`
  - **Requirements**: 14
  - **Dependencies**: 3, 4, 7, 9, 11, 12, 13, 16, 17, 18

- [x] 25. Aksesibilitas & SEO Final
  - Audit seluruh halaman publik: pastikan `<html lang="id">` ada di semua halaman, heading hierarchy benar (tidak ada lompatan dari `<h1>` ke `<h3>`), setiap `<img>` punya `alt` (deskriptif untuk informatif, `alt=""` untuk dekoratif)
  - Pastikan semua elemen interaktif (tombol, link, input) dapat difokuskan via keyboard dengan urutan tab logis dan indikator fokus visual yang terlihat (tidak dihapus dengan `outline: none` tanpa pengganti)
  - Pastikan semua halaman publik menggunakan landmark HTML semantik: `<header>`, `<nav>`, `<main>`, `<footer>`, `<article>` untuk konten, `<aside>` jika perlu
  - Audit dan pastikan semua halaman publik menyematkan meta tags lengkap: `<title>`, `<meta name="description">`, `<link rel="canonical">`, Open Graph tags (og:title, og:description, og:image, og:url, og:type)
  - Pastikan Schema Markup hanya dirender saat semua field wajib terisi: NewsArticle (title + published_at + author), Announcement (title + published_at), EducationalOrganization (school_name + school_address), Event/PPDB (ppdb_start_date + ppdb_end_date), LocalBusiness (semua 5 field koordinat + info)
  - Implementasi FAQPage schema di halaman yang memiliki seksi FAQ (jika ada FAQ aktif dari `Faq::active()->ordered()->get()`)
  - Pastikan LocalBusiness schema dirender di semua halaman publik secara kondisional melalui View Composer di `AppServiceProvider`
  - Pastikan lazy loading (`loading="lazy"`) diterapkan pada semua gambar yang tidak berada di above-the-fold; hero/banner images menggunakan `loading="eager"` atau `fetchpriority="high"`
  - Tulis test di `tests/Feature/Public/SeoAuditTest.php`: setiap halaman publik punya `lang="id"`, meta description tidak kosong, canonical URL ada, Schema JSON-LD valid dan dapat di-parse, halaman tanpa data wajib tidak merender Schema
  - **Requirements**: 9, 10, 13
  - **Dependencies**: 6, 7, 8, 9, 10, 11, 12, 13, 14, 22
