# Technical Design Document
## Website SMK Muda Bawean — Laravel Native

**Versi:** 1.0  
**Tanggal:** 2025  
**Status:** Draft  
**Bahasa Implementasi:** PHP 8.2+ / Laravel 11

---

## 1. Architecture Overview

### 1.1 Technology Stack

| Layer | Teknologi |
|-------|-----------|
| Framework | Laravel 11 |
| Database | MySQL 8.0 |
| Template Engine | Blade + Vanilla JavaScript |
| CSS Framework | TailwindCSS (via CDN Play CDN untuk dev, atau `npm run build` output di-commit) |
| Cache | File-based cache (Laravel `file` driver) |
| Queue | Sync driver (tanpa daemon, kompatibel shared hosting) |
| Server | Apache / LiteSpeed (shared hosting) |
| Storage | File system lokal (`storage/`) |

### 1.2 Architectural Pattern

Aplikasi menggunakan pola **MVC** dengan tambahan **Service Layer** dan **Repository Pattern ringan**:

```
Request → Router → Middleware → Controller → Service → Model/Repository → Database
                                     ↓
                               View (Blade)
```

- **Controller**: Hanya menangani HTTP request/response, delegasi logika ke Service.
- **Service**: Mengandung business logic (SlugService, HtmlSanitizerService, dll).
- **Model**: Eloquent ORM, relasi, scope query.
- **Repository**: Opsional — digunakan hanya untuk query kompleks yang dipakai di banyak tempat.

### 1.3 Deployment Constraints

- **Target**: Shared hosting (cPanel/DirectAdmin).
- **Tidak tersedia**: `supervisor`, queue daemon, Redis, Memcached.
- **Queue driver**: `sync` — job dieksekusi langsung di request yang sama.
- **Cache driver**: `file` — disimpan di `storage/framework/cache/`.
- **Session driver**: `file` — disimpan di `storage/framework/sessions/`.
- **Cron job**: Tersedia terbatas (cPanel cron), hanya untuk pembersihan cache jika diperlukan.

---

## 2. Database Schema

### 2.1 Tabel `users`

```sql
CREATE TABLE users (
    id              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(100)    NOT NULL,
    email           VARCHAR(255)    NOT NULL,
    password        VARCHAR(255)    NOT NULL,
    role            ENUM('admin','editor') NOT NULL DEFAULT 'editor',
    login_attempts  TINYINT UNSIGNED NOT NULL DEFAULT 0,
    locked_until    TIMESTAMP       NULL,
    remember_token  VARCHAR(100)    NULL,
    created_at      TIMESTAMP       NULL,
    updated_at      TIMESTAMP       NULL,
    UNIQUE KEY uq_users_email (email)
);
```

### 2.2 Tabel `settings`

```sql
CREATE TABLE settings (
    id         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    key        VARCHAR(100)    NOT NULL,
    value      TEXT            NULL,
    created_at TIMESTAMP       NULL,
    updated_at TIMESTAMP       NULL,
    UNIQUE KEY uq_settings_key (`key`)
);
```

**Key yang digunakan:**
| Key | Keterangan | Nilai Default |
|-----|------------|---------------|
| `color_primary` | Warna primer tema | `#16a34a` |
| `color_secondary` | Warna sekunder tema | `#15803d` |
| `color_accent` | Warna aksen tema | `#bbf7d0` |
| `school_name` | Nama sekolah | `SMK Muda Bawean` |
| `school_address` | Alamat lengkap | - |
| `school_phone` | Nomor telepon | - |
| `school_email` | Email sekolah | - |
| `school_geo_lat` | Koordinat latitude | - |
| `school_geo_lng` | Koordinat longitude | - |
| `principal_greeting` | Sambutan kepala sekolah (HTML) | - |
| `robots_txt` | Konten file robots.txt | `User-agent: *\nAllow: /` |
| `ppdb_is_active` | Status periode PPDB aktif | `0` |
| `ppdb_start_date` | Tanggal mulai PPDB | - |
| `ppdb_end_date` | Tanggal selesai PPDB | - |
| `social_facebook` | URL Facebook | - |
| `social_instagram` | URL Instagram | - |
| `social_youtube` | URL YouTube | - |

### 2.3 Tabel `pages`

```sql
CREATE TABLE pages (
    id               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    slug             VARCHAR(255)    NOT NULL,
    title            VARCHAR(255)    NOT NULL,
    content          LONGTEXT        NULL,
    meta_title       VARCHAR(60)     NULL,
    meta_description VARCHAR(160)    NULL,
    created_at       TIMESTAMP       NULL,
    updated_at       TIMESTAMP       NULL,
    UNIQUE KEY uq_pages_slug (slug)
);
```

**Slug yang di-seed:** `sejarah`, `visi-misi`

### 2.4 Tabel `teachers`

```sql
CREATE TABLE teachers (
    id         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100)    NOT NULL,
    position   VARCHAR(150)    NOT NULL,
    photo      VARCHAR(255)    NULL,
    `order`    SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP       NULL,
    updated_at TIMESTAMP       NULL,
    INDEX idx_teachers_order (`order`)
);
```

### 2.5 Tabel `posts`

```sql
CREATE TABLE posts (
    id               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title            VARCHAR(255)    NOT NULL,
    slug             VARCHAR(255)    NOT NULL,
    content          LONGTEXT        NULL,
    excerpt          TEXT            NULL,
    thumbnail        VARCHAR(255)    NULL,
    status           ENUM('draft','published') NOT NULL DEFAULT 'draft',
    meta_title       VARCHAR(60)     NULL,
    meta_description VARCHAR(160)    NULL,
    author_id        BIGINT UNSIGNED NOT NULL,
    published_at     TIMESTAMP       NULL,
    deleted_at       TIMESTAMP       NULL,
    created_at       TIMESTAMP       NULL,
    updated_at       TIMESTAMP       NULL,
    UNIQUE KEY uq_posts_slug (slug),
    INDEX idx_posts_status (status),
    INDEX idx_posts_published_at (published_at),
    INDEX idx_posts_status_published (status, published_at),
    CONSTRAINT fk_posts_author FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE RESTRICT
);
```

**Catatan:** `deleted_at` digunakan untuk soft delete. Saat post dihapus (soft delete), controller mengembalikan HTTP 410 Gone.

### 2.6 Tabel `announcements`

```sql
CREATE TABLE announcements (
    id               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title            VARCHAR(255)    NOT NULL,
    slug             VARCHAR(255)    NOT NULL,
    content          LONGTEXT        NULL,
    attachment       VARCHAR(255)    NULL,
    status           ENUM('draft','published') NOT NULL DEFAULT 'draft',
    meta_title       VARCHAR(60)     NULL,
    meta_description VARCHAR(160)    NULL,
    published_at     TIMESTAMP       NULL,
    created_at       TIMESTAMP       NULL,
    updated_at       TIMESTAMP       NULL,
    UNIQUE KEY uq_announcements_slug (slug),
    INDEX idx_announcements_status (status),
    INDEX idx_announcements_published_at (published_at),
    INDEX idx_announcements_status_published (status, published_at)
);
```

### 2.7 Tabel `graduations`

```sql
CREATE TABLE graduations (
    id                BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    academic_year     VARCHAR(9)      NOT NULL,  -- format: "2023/2024"
    student_name      VARCHAR(100)    NOT NULL,
    exam_number       VARCHAR(50)     NOT NULL,
    major             VARCHAR(100)    NOT NULL,
    graduation_status ENUM('LULUS','TIDAK LULUS') NOT NULL,
    created_at        TIMESTAMP       NULL,
    updated_at        TIMESTAMP       NULL,
    INDEX idx_graduations_academic_year (academic_year),
    INDEX idx_graduations_student_name (student_name),
    INDEX idx_graduations_exam_number (exam_number)
);
```

### 2.8 Tabel `alumni`

```sql
CREATE TABLE alumni (
    id              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    full_name       VARCHAR(100)    NOT NULL,
    graduation_year SMALLINT UNSIGNED NOT NULL,
    email           VARCHAR(255)    NOT NULL,
    phone           VARCHAR(20)     NULL,
    address         TEXT            NULL,
    created_at      TIMESTAMP       NULL,
    updated_at      TIMESTAMP       NULL,
    UNIQUE KEY uq_alumni_email (email),
    INDEX idx_alumni_graduation_year (graduation_year)
);
```

### 2.9 Tabel `tracer_studies`

```sql
CREATE TABLE tracer_studies (
    id                 BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    alumni_id          BIGINT UNSIGNED NULL,
    full_name          VARCHAR(100)    NOT NULL,
    graduation_year    SMALLINT UNSIGNED NOT NULL,
    education_status   VARCHAR(100)    NOT NULL,  -- "Melanjutkan", "Tidak Melanjutkan"
    employment_status  VARCHAR(100)    NOT NULL,  -- "Bekerja", "Wirausaha", "Tidak Bekerja"
    employer_name      VARCHAR(150)    NULL,
    position           VARCHAR(100)    NULL,
    created_at         TIMESTAMP       NULL,
    updated_at         TIMESTAMP       NULL,
    INDEX idx_tracer_alumni_id (alumni_id),
    INDEX idx_tracer_graduation_year (graduation_year),
    CONSTRAINT fk_tracer_alumni FOREIGN KEY (alumni_id) REFERENCES alumni(id) ON DELETE SET NULL
);
```

### 2.10 Tabel `ppdb_registrations`

```sql
CREATE TABLE ppdb_registrations (
    id                  BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    registration_number VARCHAR(30)     NOT NULL,  -- PPDB-YYYYMMDD-0001
    full_name           VARCHAR(100)    NOT NULL,
    birth_place         VARCHAR(50)     NOT NULL,
    birth_date          DATE            NOT NULL,
    school_origin       VARCHAR(100)    NOT NULL,
    parent_name         VARCHAR(100)    NOT NULL,
    phone               VARCHAR(13)     NOT NULL,
    status              ENUM('menunggu','diterima','ditolak') NOT NULL DEFAULT 'menunggu',
    created_at          TIMESTAMP       NULL,
    updated_at          TIMESTAMP       NULL,
    UNIQUE KEY uq_ppdb_registration_number (registration_number),
    INDEX idx_ppdb_status (status),
    INDEX idx_ppdb_created_at (created_at)
);
```

### 2.11 Tabel `faqs`

```sql
CREATE TABLE faqs (
    id         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    question   TEXT            NOT NULL,
    answer     TEXT            NOT NULL,
    `order`    SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    is_active  TINYINT(1)      NOT NULL DEFAULT 1,
    created_at TIMESTAMP       NULL,
    updated_at TIMESTAMP       NULL,
    INDEX idx_faqs_order (`order`),
    INDEX idx_faqs_is_active (is_active)
);
```

---

## 3. Application Structure

### 3.1 Direktori Lengkap

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Public/
│   │   │   ├── HomeController.php
│   │   │   ├── NewsController.php
│   │   │   ├── AnnouncementController.php
│   │   │   ├── GraduationController.php
│   │   │   ├── AlumniController.php
│   │   │   ├── PpdbController.php
│   │   │   ├── ProfileController.php
│   │   │   └── SitemapController.php
│   │   ├── Admin/
│   │   │   ├── DashboardController.php
│   │   │   ├── PostController.php
│   │   │   ├── AnnouncementController.php
│   │   │   ├── GraduationController.php
│   │   │   ├── AlumniController.php
│   │   │   ├── PpdbController.php
│   │   │   ├── TeacherController.php
│   │   │   ├── SettingController.php
│   │   │   ├── PageController.php
│   │   │   ├── FaqController.php
│   │   │   └── UserController.php
│   │   └── Auth/
│   │       └── LoginController.php
│   ├── Middleware/
│   │   ├── AdminAuth.php          -- redirect jika belum login
│   │   ├── RoleCheck.php          -- cek role admin/editor, return 403 jika tidak berwenang
│   │   └── RateLimitLogin.php     -- throttle login attempt per IP
│   └── Requests/
│       ├── StorePpdbRequest.php
│       ├── StoreAlumniRequest.php
│       ├── StoreTracerStudyRequest.php
│       ├── StorePostRequest.php
│       ├── UpdatePostRequest.php
│       ├── StoreAnnouncementRequest.php
│       ├── StoreTeacherRequest.php
│       ├── StoreSettingRequest.php
│       ├── StorePageRequest.php
│       ├── StoreFaqRequest.php
│       └── StoreUserRequest.php
├── Models/
│   ├── User.php
│   ├── Post.php
│   ├── Announcement.php
│   ├── Graduation.php
│   ├── Alumni.php
│   ├── TracerStudy.php
│   ├── PpdbRegistration.php
│   ├── Teacher.php
│   ├── Setting.php
│   ├── Page.php
│   └── Faq.php
├── Services/
│   ├── SlugService.php
│   ├── HtmlSanitizerService.php
│   ├── CsvService.php
│   ├── SitemapService.php
│   ├── SchemaMarkupService.php
│   ├── ThemeService.php
│   └── CacheService.php
├── Helpers/
│   └── helpers.php               -- fungsi global: seo_meta(), schema_json_ld(), theme_colors()
├── Observers/
│   ├── PostObserver.php
│   ├── AnnouncementObserver.php
│   ├── PageObserver.php
│   ├── TeacherObserver.php
│   └── SettingObserver.php
└── Providers/
    └── AppServiceProvider.php    -- register observer, helper, binding service

resources/
├── views/
│   ├── layouts/
│   │   ├── public.blade.php
│   │   └── admin.blade.php
│   ├── public/
│   │   ├── home/
│   │   │   └── index.blade.php
│   │   ├── profil/
│   │   │   ├── sejarah.blade.php
│   │   │   ├── visi-misi.blade.php
│   │   │   └── pendidik.blade.php
│   │   ├── berita/
│   │   │   ├── index.blade.php
│   │   │   └── show.blade.php
│   │   ├── pengumuman/
│   │   │   ├── index.blade.php
│   │   │   └── show.blade.php
│   │   ├── kelulusan/
│   │   │   └── index.blade.php
│   │   ├── alumni/
│   │   │   ├── index.blade.php
│   │   │   └── tracer-study.blade.php
│   │   ├── ppdb/
│   │   │   └── index.blade.php
│   │   └── errors/
│   │       ├── 404.blade.php
│   │       ├── 410.blade.php
│   │       └── 403.blade.php
│   ├── admin/
│   │   ├── dashboard/
│   │   │   └── index.blade.php
│   │   ├── posts/
│   │   │   ├── index.blade.php
│   │   │   ├── create.blade.php
│   │   │   └── edit.blade.php
│   │   ├── announcements/
│   │   ├── graduations/
│   │   ├── alumni/
│   │   ├── ppdb/
│   │   ├── teachers/
│   │   ├── settings/
│   │   │   ├── index.blade.php
│   │   │   └── seo.blade.php
│   │   ├── pages/
│   │   ├── faqs/
│   │   └── users/
│   └── components/
│       ├── hero.blade.php
│       ├── news-card.blade.php
│       ├── announcement-item.blade.php
│       ├── pagination.blade.php
│       ├── breadcrumb.blade.php
│       ├── seo-head.blade.php
│       └── schema-markup.blade.php

routes/
├── web.php         -- public routes
└── admin.php       -- admin routes (prefix /admin)

database/
├── migrations/     -- lihat urutan di seksi 8
└── seeders/
    ├── AdminUserSeeder.php
    └── SettingSeeder.php
```

---

## 4. Key Technical Decisions

### 4.1 Caching Strategy

**Driver:** `file` (Laravel default file cache) — simpan di `storage/framework/cache/data/`.

#### Cache Keys & TTL

| Cache Key | Konten | TTL |
|-----------|--------|-----|
| `home_page` | Seluruh data halaman Home | 3600 detik (1 jam) |
| `profil_sejarah` | Konten halaman Sejarah | 3600 detik |
| `profil_visi_misi` | Konten halaman Visi Misi | 3600 detik |
| `profil_pendidik` | Data semua Pendidik | 3600 detik |
| `page_{slug}` | Konten halaman statis | 3600 detik |
| `settings_all` | Semua setting dari DB | 3600 detik |
| `theme_colors` | Tiga nilai Theme_Color | 3600 detik |
| `sitemap_xml` | Output XML sitemap | 60 detik |
| `faqs_active` | Daftar FAQ aktif | 3600 detik |

#### Implementasi Stale-While-Revalidate

Karena tidak ada background job daemon, strategi SWR diimplementasikan dengan dual TTL key:

```php
// CacheService::rememberStale()
// Key utama: konten, TTL = $ttl
// Key stale flag: "stale_{key}", TTL = $ttl + grace period (300 detik)
//
// Alur:
// 1. Jika key utama ada → return langsung
// 2. Jika key utama expired tapi key stale ada → return nilai stale,
//    simpan job regenerasi ke sync queue (dieksekusi akhir request)
// 3. Jika keduanya expired → eksekusi callback, simpan fresh, return
```

Implementasi konkret:

```php
public function rememberStale(string $key, int $ttl, callable $callback): mixed
{
    $staleKey = "stale_{$key}";
    $gracePeriod = 300; // 5 menit

    if ($value = Cache::get($key)) {
        return $value;
    }

    $staleValue = Cache::get($staleKey);

    if ($staleValue !== null) {
        // Sajikan stale, regenerasi di background (sync = langsung di akhir request)
        dispatch(function () use ($key, $staleKey, $ttl, $gracePeriod, $callback) {
            $fresh = $callback();
            Cache::put($key, $fresh, $ttl);
            Cache::put($staleKey, $fresh, $ttl + $gracePeriod);
        })->afterResponse();

        return $staleValue;
    }

    $fresh = $callback();
    Cache::put($key, $fresh, $ttl);
    Cache::put($staleKey, $fresh, $ttl + $gracePeriod);
    return $fresh;
}
```

#### Cache Invalidation via Observer

Setiap kali konten diubah, Observer terkait memanggil `CacheService::forget()`:

```php
// PostObserver.php
public function saved(Post $post): void
{
    Cache::forget('home_page');
    Cache::forget("post_{$post->slug}");
    Cache::forget('sitemap_xml');
}

public function deleted(Post $post): void
{
    Cache::forget('home_page');
    Cache::forget("post_{$post->slug}");
    Cache::forget('sitemap_xml');
}
```

Observer yang diperlukan:
- `PostObserver` → invalidasi `home_page`, `post_{slug}`, `sitemap_xml`
- `AnnouncementObserver` → invalidasi `home_page`, `announcement_{slug}`, `sitemap_xml`
- `PageObserver` → invalidasi `page_{slug}`, `profil_{slug}`
- `TeacherObserver` → invalidasi `profil_pendidik`
- `SettingObserver` → invalidasi `settings_all`, `theme_colors`

### 4.2 Theme Color System

**Penyimpanan:** Tabel `settings` dengan key `color_primary`, `color_secondary`, `color_accent`.

**Rendering:** Setiap request halaman publik membaca theme colors dari cache, lalu merender inline `<style>` di `<head>`:

```html
<style>
  :root {
    --color-primary:   {{ $theme['color_primary'] }};
    --color-secondary: {{ $theme['color_secondary'] }};
    --color-accent:    {{ $theme['color_accent'] }};
  }
</style>
```

**Validasi di `StoreSettingRequest`:**

```php
'color_primary'   => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
'color_secondary' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
'color_accent'    => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
```

**Live Preview via Vanilla JS** (di halaman settings admin):

```javascript
document.querySelectorAll('[data-color-preview]').forEach(function(input) {
    input.addEventListener('input', function() {
        var prop = this.dataset.colorPreview; // e.g. "--color-primary"
        document.documentElement.style.setProperty(prop, this.value);
    });
});
```

**ThemeService:**

```php
class ThemeService
{
    public function getColors(): array
    {
        return Cache::remember('theme_colors', 3600, function () {
            return [
                'color_primary'   => Setting::get('color_primary', '#16a34a'),
                'color_secondary' => Setting::get('color_secondary', '#15803d'),
                'color_accent'    => Setting::get('color_accent', '#bbf7d0'),
            ];
        });
    }
}
```

### 4.3 SEO & Schema Markup

#### Meta Tag Flow

Setiap public controller mengumpulkan data SEO dan meneruskannya ke view:

```php
// Contoh di NewsController@show
public function show(string $slug): View|Response
{
    $post = Post::published()->where('slug', $slug)->firstOrFail();

    $seo = [
        'title'       => $post->meta_title ?: $post->title,
        'description' => $post->meta_description ?: Str::limit(strip_tags($post->content), 160),
        'og_title'    => $post->meta_title ?: $post->title,
        'og_description' => $post->meta_description ?: Str::limit(strip_tags($post->content), 160),
        'og_image'    => $post->thumbnail ? Storage::url($post->thumbnail) : null,
        'og_url'      => route('berita.show', $post->slug),
        'canonical'   => route('berita.show', $post->slug),
    ];

    $schema = $this->schemaService->newsArticle($post);

    return view('public.berita.show', compact('post', 'seo', 'schema'));
}
```

#### Komponen `components/seo-head.blade.php`

```blade
<title>{{ $seo['title'] }} | SMK Muda Bawean</title>
<meta name="description" content="{{ $seo['description'] }}">
<link rel="canonical" href="{{ $seo['canonical'] ?? request()->url() }}">

<meta property="og:type"        content="website">
<meta property="og:title"       content="{{ $seo['og_title'] ?? $seo['title'] }}">
<meta property="og:description" content="{{ $seo['og_description'] ?? $seo['description'] }}">
<meta property="og:url"         content="{{ $seo['og_url'] ?? request()->url() }}">
@if (!empty($seo['og_image']))
<meta property="og:image"       content="{{ $seo['og_image'] }}">
@endif
```

#### Schema Markup Rendering

Schema hanya dirender jika tidak null (semua field wajib terisi):

```blade
{{-- components/schema-markup.blade.php --}}
@if (!empty($schema))
<script type="application/ld+json">
{!! json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endif
```

#### Sitemap

Route `/sitemap.xml` → `SitemapController@index`:

```php
public function index(): Response
{
    $xml = $this->sitemapService->generate();
    return response($xml, 200)->header('Content-Type', 'application/xml');
}
```

`SitemapService::generate()` mengquery semua konten publik dan cache hasilnya 60 detik.

### 4.4 Authentication & Security

#### Autentikasi

- **Mekanisme:** Laravel built-in session-based auth (`Auth::attempt()`).
- **Guard:** `web` (default).
- **Session timeout:** 60 menit — dikonfigurasi di `config/session.php` → `lifetime = 60`.

#### Middleware Stack `/admin/*`

```
web → AdminAuth → RoleCheck → [controller action]
```

- `AdminAuth`: Jika `!Auth::check()` → redirect ke `/admin/login`.
- `RoleCheck`: Untuk resource yang hanya bisa diakses admin → jika `auth()->user()->role !== 'admin'` → abort(403).

#### Login Rate Limiting

`RateLimitLogin` middleware menggunakan kombinasi IP + email sebagai throttle key:

```php
// RateLimitLogin.php
$key = 'login_' . sha1($request->ip() . '|' . $request->input('email'));
if (RateLimiter::tooManyAttempts($key, 10)) {
    return response()->json(['message' => 'Too many attempts.'], 429);
}
```

Selain rate limiting di middleware, `LoginController` juga mengelola `login_attempts` dan `locked_until` di tabel `users`:

```php
// LoginController@login
if ($user->locked_until && now()->lt($user->locked_until)) {
    return back()->withErrors(['email' => 'Akun dikunci hingga ' . $user->locked_until->format('H:i')]);
}

if (!Auth::attempt($credentials)) {
    $user->increment('login_attempts');
    if ($user->login_attempts >= 5) {
        $user->update(['locked_until' => now()->addMinutes(15)]);
    }
    return back()->withErrors(['email' => 'Kredensial salah.']);
}

$user->update(['login_attempts' => 0, 'locked_until' => null]);
```

#### HTML Sanitization

Setiap rich text yang disimpan melalui Admin Panel **wajib** disanitasi via `HtmlSanitizerService` sebelum masuk ke database. Ini dilakukan di Service layer, bukan di Controller.

```php
// PostService@store
$data['content'] = $this->sanitizer->clean($data['content']);
$post = Post::create($data);
```

#### File Upload Security

- Semua file upload disimpan di `storage/app/private/uploads/` (di luar `public/`).
- Nama file diganti dengan UUID: `Str::uuid() . '.' . $file->getClientOriginalExtension()`.
- Validasi MIME type via `mimes:jpg,jpeg,png,webp` (thumbnail) dan `mimes:pdf` (lampiran).
- Ukuran maksimal 2MB: `max:2048`.
- Symlink untuk thumbnail: `storage/app/public/thumbnails/` → `public/storage/thumbnails/` (dibuat via `php artisan storage:link`).
- Download lampiran via controller stream (bukan akses langsung):

```php
// AnnouncementController@download (public)
public function download(string $slug): BinaryFileResponse
{
    $announcement = Announcement::published()->where('slug', $slug)->firstOrFail();

    abort_if(!$announcement->attachment, 404);

    $path = storage_path('app/private/uploads/' . $announcement->attachment);
    abort_if(!file_exists($path), 404);

    return response()->download($path);
}
```

### 4.5 Slug Generation

`SlugService::generate()` menghasilkan slug unik per model:

```php
class SlugService
{
    public function generate(string $title, string $modelClass, ?int $excludeId = null): string
    {
        $base = $this->slugify($title);
        $slug = $base;
        $counter = 1;

        while ($this->exists($slug, $modelClass, $excludeId)) {
            $slug = $base . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    private function slugify(string $title): string
    {
        $slug = mb_strtolower($title, 'UTF-8');
        $slug = preg_replace('/[^a-z0-9\s-]/u', '', $slug);
        $slug = preg_replace('/[\s-]+/', '-', $slug);
        return trim($slug, '-');
    }

    private function exists(string $slug, string $modelClass, ?int $excludeId): bool
    {
        $query = $modelClass::where('slug', $slug);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        return $query->exists();
    }
}
```

### 4.6 CSV Import/Export

**Import Kelulusan:**

```php
class CsvService
{
    public function importGraduations(UploadedFile $file, string $academicYear): array
    {
        $requiredColumns = ['nama_siswa', 'nomor_peserta', 'program_keahlian', 'status_kelulusan'];
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle);
        $header = array_map('trim', array_map('strtolower', $header));

        $missing = array_diff($requiredColumns, $header);
        if (!empty($missing)) {
            fclose($handle);
            throw new CsvHeaderException('Kolom tidak ditemukan: ' . implode(', ', $missing));
        }

        $imported = 0; $failed = 0; $errors = [];
        $row = 1;

        while (($data = fgetcsv($handle)) !== false) {
            $row++;
            $record = array_combine($header, $data);
            try {
                $this->validateAndInsertGraduation($record, $academicYear);
                $imported++;
            } catch (\Exception $e) {
                $failed++;
                $errors[] = "Baris {$row}: " . $e->getMessage();
            }
        }

        fclose($handle);
        return compact('imported', 'failed', 'errors');
    }
}
```

**Export PPDB ke CSV** menggunakan `fputcsv` native PHP untuk menghindari dependensi tambahan. `League\Csv` dapat digunakan sebagai alternatif jika sudah tersedia di composer.

### 4.7 Nomor Registrasi PPDB

Format: `PPDB-{YYYYMMDD}-{nomor_urut_4_digit}`

Contoh: `PPDB-20250115-0001`

Implementasi dengan pessimistic locking untuk mencegah race condition:

```php
// PpdbService@generateRegistrationNumber
public function generateRegistrationNumber(): string
{
    return DB::transaction(function () {
        $today = now()->format('Ymd');
        $prefix = "PPDB-{$today}-";

        $last = PpdbRegistration::where('registration_number', 'like', $prefix . '%')
            ->lockForUpdate()
            ->orderByDesc('registration_number')
            ->first();

        $sequence = $last
            ? (int) substr($last->registration_number, -4) + 1
            : 1;

        return $prefix . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    });
}
```

---

## 5. Routes

### 5.1 Public Routes (`routes/web.php`)

```php
<?php

use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\NewsController;
use App\Http\Controllers\Public\AnnouncementController;
use App\Http\Controllers\Public\GraduationController;
use App\Http\Controllers\Public\AlumniController;
use App\Http\Controllers\Public\PpdbController;
use App\Http\Controllers\Public\ProfileController;
use App\Http\Controllers\Public\SitemapController;

// Halaman Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Berita
Route::prefix('berita')->name('berita.')->group(function () {
    Route::get('/',        [NewsController::class, 'index'])->name('index');
    Route::get('/{slug}',  [NewsController::class, 'show'])->name('show');
});

// Pengumuman
Route::prefix('pengumuman')->name('pengumuman.')->group(function () {
    Route::get('/',                  [AnnouncementController::class, 'index'])->name('index');
    Route::get('/{slug}',            [AnnouncementController::class, 'show'])->name('show');
    Route::get('/{slug}/download',   [AnnouncementController::class, 'download'])->name('download');
});

// Profil — urutan route penting: spesifik dulu, wildcard belakang
Route::prefix('profil')->name('profil.')->group(function () {
    Route::get('/sejarah',   [ProfileController::class, 'sejarah'])->name('sejarah');
    Route::get('/visi-misi', [ProfileController::class, 'visiMisi'])->name('visi-misi');
    Route::get('/pendidik',  [ProfileController::class, 'pendidik'])->name('pendidik');
    Route::get('/{any}',     fn() => abort(404));  // catch-all → 404
});

// Kelulusan
Route::get('/kelulusan', [GraduationController::class, 'index'])->name('kelulusan.index');

// Alumni & Tracer Study
Route::prefix('alumni')->name('alumni.')->group(function () {
    Route::get('/',                     [AlumniController::class, 'index'])->name('index');
    Route::post('/',                    [AlumniController::class, 'store'])->name('store');
    Route::get('/tracer-study',         [AlumniController::class, 'tracerStudy'])->name('tracer-study');
    Route::post('/tracer-study',        [AlumniController::class, 'storeTracerStudy'])->name('tracer-study.store');
});

// PPDB
Route::prefix('ppdb')->name('ppdb.')->group(function () {
    Route::get('/',   [PpdbController::class, 'index'])->name('index');
    Route::post('/',  [PpdbController::class, 'store'])->name('store');
});

// SEO
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt',  [SitemapController::class, 'robots'])->name('robots');
```

### 5.2 Admin Routes (`routes/admin.php`)

```php
<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\GraduationController;
use App\Http\Controllers\Admin\AlumniController;
use App\Http\Controllers\Admin\PpdbController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\UserController;

Route::prefix('admin')->name('admin.')->group(function () {

    // Autentikasi (tidak memerlukan middleware auth)
    Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])
         ->middleware('throttle:10,1')
         ->name('login.submit');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Route yang memerlukan autentikasi
    Route::middleware(['admin.auth'])->group(function () {

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Berita
        Route::resource('posts', PostController::class);

        // Pengumuman
        Route::resource('announcements', AnnouncementController::class);

        // Kelulusan
        Route::resource('graduations', GraduationController::class);
        Route::post('graduations/import', [GraduationController::class, 'import'])->name('graduations.import');

        // Alumni & Tracer Study
        Route::resource('alumni', AlumniController::class);
        Route::get('tracer-studies', [AlumniController::class, 'tracerStudies'])->name('tracer-studies.index');

        // PPDB
        Route::resource('ppdb', PpdbController::class)->only(['index', 'show', 'update']);
        Route::get('ppdb/export', [PpdbController::class, 'export'])->name('ppdb.export');

        // Pendidik
        Route::resource('teachers', TeacherController::class);

        // Halaman Statis
        Route::resource('pages', PageController::class)->only(['index', 'show', 'edit', 'update']);

        // FAQ
        Route::resource('faqs', FaqController::class);

        // Pengguna (hanya admin)
        Route::resource('users', UserController::class)->middleware('role:admin');

        // Pengaturan (hanya admin)
        Route::get('settings',         [SettingController::class, 'index'])->name('settings.index');
        Route::post('settings',        [SettingController::class, 'update'])->name('settings.update');
        Route::get('settings/seo',     [SettingController::class, 'seo'])->name('settings.seo');
        Route::post('settings/seo',    [SettingController::class, 'updateSeo'])->name('settings.seo.update');
        Route::get('settings/theme',   [SettingController::class, 'theme'])->name('settings.theme');
        Route::post('settings/theme',  [SettingController::class, 'updateTheme'])->name('settings.theme.update');
    });
});
```

**Registrasi admin.php di `bootstrap/app.php`:**

```php
->withRouting(
    web: __DIR__.'/../routes/web.php',
    then: function () {
        Route::middleware('web')
             ->group(base_path('routes/admin.php'));
    }
)
```

---

## 6. Component Architecture (Blade)

### 6.1 Layout: `layouts/public.blade.php`

```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Theme Colors: inline CSS vars dari database --}}
    <style>
        :root {
            --color-primary:   {{ $themeColors['color_primary'] }};
            --color-secondary: {{ $themeColors['color_secondary'] }};
            --color-accent:    {{ $themeColors['color_accent'] }};
        }
    </style>

    {{-- SEO Meta Tags --}}
    @include('components.seo-head', $seo ?? [])

    {{-- TailwindCSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    @yield('head')
</head>
<body class="min-h-screen flex flex-col">

    {{-- Header & Navigasi --}}
    <header role="banner">
        <nav role="navigation" aria-label="Navigasi utama">
            {{-- Logo --}}
            <a href="{{ route('home') }}">
                <img src="/images/logo.png" alt="{{ $schoolName }} Logo" width="120" height="60">
            </a>

            {{-- Menu Desktop --}}
            <ul id="nav-menu" class="hidden md:flex">
                <li><a href="{{ route('home') }}">Beranda</a></li>
                <li>
                    <button aria-expanded="false" aria-haspopup="true" aria-controls="menu-profil">Profil</button>
                    <ul id="menu-profil" role="menu">
                        <li><a href="{{ route('profil.sejarah') }}" role="menuitem">Sejarah</a></li>
                        <li><a href="{{ route('profil.visi-misi') }}" role="menuitem">Visi &amp; Misi</a></li>
                        <li><a href="{{ route('profil.pendidik') }}" role="menuitem">Pendidik</a></li>
                    </ul>
                </li>
                <li><a href="{{ route('berita.index') }}">Berita</a></li>
                <li><a href="{{ route('pengumuman.index') }}">Pengumuman</a></li>
                <li><a href="{{ route('kelulusan.index') }}">Kelulusan</a></li>
                <li><a href="{{ route('alumni.index') }}">Alumni</a></li>
                <li><a href="{{ route('ppdb.index') }}">PPDB</a></li>
            </ul>

            {{-- Hamburger (mobile) --}}
            <button id="nav-toggle" aria-controls="nav-menu" aria-expanded="false"
                    aria-label="Buka menu navigasi" class="md:hidden">
                <span aria-hidden="true">☰</span>
            </button>
        </nav>
    </header>

    {{-- Breadcrumb --}}
    @hasSection('breadcrumb')
        @include('components.breadcrumb')
    @endif

    {{-- Konten Utama --}}
    <main id="main-content" role="main" tabindex="-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer role="contentinfo">
        {{-- Info sekolah, tautan, media sosial --}}
    </footer>

    {{-- Schema Markup --}}
    @include('components.schema-markup', ['schema' => $schema ?? null])

    {{-- Vanilla JS: hamburger menu --}}
    <script>
        const toggle = document.getElementById('nav-toggle');
        const menu = document.getElementById('nav-menu');
        toggle.addEventListener('click', function() {
            const expanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', String(!expanded));
            menu.classList.toggle('hidden');
        });
    </script>

    @yield('scripts')
</body>
</html>
```

**Data yang di-share ke semua view publik via `AppServiceProvider` atau View Composer:**

```php
// AppServiceProvider@boot
View::composer('layouts.public', function ($view) {
    $view->with('themeColors', app(ThemeService::class)->getColors());
    $view->with('schoolName', Setting::get('school_name', 'SMK Muda Bawean'));
});
```

### 6.2 Layout: `layouts/admin.blade.php`

```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') | SMK Muda Bawean</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex h-screen">

    {{-- Sidebar --}}
    <aside role="complementary" aria-label="Menu admin">
        <nav>
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('admin.posts.index') }}">Berita</a>
            <a href="{{ route('admin.announcements.index') }}">Pengumuman</a>
            <a href="{{ route('admin.graduations.index') }}">Kelulusan</a>
            <a href="{{ route('admin.alumni.index') }}">Alumni</a>
            <a href="{{ route('admin.ppdb.index') }}">PPDB</a>
            <a href="{{ route('admin.teachers.index') }}">Pendidik</a>
            <a href="{{ route('admin.pages.index') }}">Halaman Statis</a>
            <a href="{{ route('admin.faqs.index') }}">FAQ</a>

            @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.users.index') }}">Pengguna</a>
            <a href="{{ route('admin.settings.index') }}">Pengaturan</a>
            @endif
        </nav>
    </aside>

    <div class="flex-1 overflow-auto">
        {{-- Header Admin --}}
        <header>
            @include('components.breadcrumb')

            {{-- Flash messages --}}
            @if(session('success'))
            <div role="alert" class="alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div role="alert" class="alert-error">{{ session('error') }}</div>
            @endif
        </header>

        <main>
            @yield('content')
        </main>
    </div>

    @yield('scripts')
</body>
</html>
```

### 6.3 Komponen Blade

#### `components/seo-head.blade.php`
Menerima array `$seo` dengan key: `title`, `description`, `og_title`, `og_description`, `og_image`, `og_url`, `canonical`.

#### `components/schema-markup.blade.php`
Menerima `$schema` (array atau null). Render `<script type="application/ld+json">` hanya jika tidak null.

#### `components/news-card.blade.php`
Menerima `$post` (Post model). Render card dengan thumbnail (lazy loading), judul, excerpt, tanggal, dan link ke detail.

#### `components/announcement-item.blade.php`
Menerima `$announcement`. Render item dengan judul, tanggal, dan badge status lampiran.

#### `components/pagination.blade.php`
Wrapper dari Laravel paginator. Menggunakan `$paginator->links()` dengan view custom yang aksesibel (aria-label pada setiap tombol halaman).

#### `components/breadcrumb.blade.php`
Menerima `$breadcrumbs` (array of `['label' => '...', 'url' => '...']`). Render `<nav aria-label="Breadcrumb">` dengan `<ol>` dan schema BreadcrumbList.

---

## 7. Service Layer

### 7.1 SlugService

```php
<?php

namespace App\Services;

class SlugService
{
    /**
     * Generate slug unik untuk model tertentu.
     *
     * @param string $title Judul yang akan dijadikan slug
     * @param string $modelClass Fully-qualified class name model (e.g. App\Models\Post::class)
     * @param int|null $excludeId ID yang dikecualikan (untuk update — jangan anggap slug sendiri sebagai duplikat)
     * @return string Slug unik
     */
    public function generate(string $title, string $modelClass, ?int $excludeId = null): string
    {
        $base = $this->slugify($title);
        $slug = $base;
        $counter = 1;

        while ($this->exists($slug, $modelClass, $excludeId)) {
            $slug = $base . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Konversi judul menjadi slug dasar.
     * Deterministik: judul yang sama selalu menghasilkan slug yang sama.
     */
    private function slugify(string $title): string
    {
        // Lowercase
        $slug = mb_strtolower($title, 'UTF-8');
        // Ganti karakter non-alphanumeric dan spasi dengan '-'
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        // Trim '-' di awal dan akhir
        return trim($slug, '-');
    }

    private function exists(string $slug, string $modelClass, ?int $excludeId): bool
    {
        $query = $modelClass::where('slug', $slug);
        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId);
        }
        return $query->exists();
    }
}
```

### 7.2 HtmlSanitizerService

Wrapper `ezyang/htmlpurifier`. Install via: `composer require ezyang/htmlpurifier`.

```php
<?php

namespace App\Services;

use HTMLPurifier;
use HTMLPurifier_Config;

class HtmlSanitizerService
{
    private HTMLPurifier $purifier;

    public function __construct()
    {
        $config = HTMLPurifier_Config::createDefault();

        // Tag yang diizinkan
        $config->set('HTML.Allowed',
            'p,br,strong,em,b,i,u,ul,ol,li,a[href|title|target],h2,h3,h4,' .
            'blockquote,img[src|alt|width|height|loading],table,thead,tbody,tr,th,td'
        );

        // Paksa target="_blank" ditambah rel="noopener noreferrer"
        $config->set('HTML.TargetBlank', true);
        $config->set('HTML.Nofollow', false);

        // Tidak izinkan inline style kecuali yang aman
        $config->set('CSS.AllowedProperties', []);

        // Cache purifier di storage
        $config->set('Cache.SerializerPath', storage_path('app/htmlpurifier'));

        $this->purifier = new HTMLPurifier($config);
    }

    /**
     * Sanitasi HTML input.
     * - Tag berbahaya (script, iframe, object, embed) dihapus.
     * - Event handler attributes (onclick, onerror, dll) dihapus.
     * - Tag yang diizinkan dipertahankan.
     */
    public function clean(string $html): string
    {
        return $this->purifier->purify($html);
    }
}
```

### 7.3 SchemaMarkupService

```php
<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Announcement;

class SchemaMarkupService
{
    /**
     * Schema EducationalOrganization untuk halaman profil sekolah.
     * Return null jika data tidak lengkap.
     */
    public function educationalOrganization(array $settings): ?array
    {
        if (empty($settings['school_name']) || empty($settings['school_address'])) {
            return null;
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@type'    => 'EducationalOrganization',
            'name'     => $settings['school_name'],
            'address'  => $settings['school_address'],
        ];

        if (!empty($settings['school_phone'])) {
            $schema['telephone'] = $settings['school_phone'];
        }
        if (!empty($settings['school_email'])) {
            $schema['email'] = $settings['school_email'];
        }

        return $schema;
    }

    /**
     * Schema NewsArticle untuk halaman detail berita.
     * Return null jika field wajib tidak lengkap.
     */
    public function newsArticle(Post $post): ?array
    {
        if (!$post->title || !$post->published_at || !$post->author) {
            return null;
        }

        $schema = [
            '@context'         => 'https://schema.org',
            '@type'            => 'NewsArticle',
            'headline'         => $post->title,
            'datePublished'    => $post->published_at->toIso8601String(),
            'dateModified'     => $post->updated_at->toIso8601String(),
            'author'           => [
                '@type' => 'Person',
                'name'  => $post->author->name,
            ],
        ];

        if ($post->thumbnail) {
            $schema['image'] = url(Storage::url($post->thumbnail));
        }

        return $schema;
    }

    /**
     * Schema untuk pengumuman (tidak ada tipe Announcement di Schema.org,
     * gunakan Article sebagai fallback terbaik).
     */
    public function announcement(Announcement $ann): ?array
    {
        if (!$ann->title || !$ann->published_at) {
            return null;
        }

        return [
            '@context'      => 'https://schema.org',
            '@type'         => 'Article',
            'headline'      => $ann->title,
            'description'   => substr(strip_tags($ann->content ?? ''), 0, 160),
            'datePublished' => $ann->published_at->toIso8601String(),
        ];
    }

    /**
     * Schema LocalBusiness.
     * Return null jika salah satu field wajib tidak terisi.
     */
    public function localBusiness(array $settings): ?array
    {
        $required = ['school_name', 'school_address', 'school_phone', 'school_geo_lat', 'school_geo_lng'];
        foreach ($required as $key) {
            if (empty($settings[$key])) {
                return null;
            }
        }

        return [
            '@context'    => 'https://schema.org',
            '@type'       => 'LocalBusiness',
            'name'        => $settings['school_name'],
            'address'     => $settings['school_address'],
            'telephone'   => $settings['school_phone'],
            'geo'         => [
                '@type'     => 'GeoCoordinates',
                'latitude'  => $settings['school_geo_lat'],
                'longitude' => $settings['school_geo_lng'],
            ],
        ];
    }

    /**
     * Schema Event untuk halaman PPDB.
     * Return null jika tanggal tidak tersedia.
     */
    public function ppdbEvent(array $settings): ?array
    {
        if (empty($settings['ppdb_start_date']) || empty($settings['ppdb_end_date'])) {
            return null;
        }

        return [
            '@context'  => 'https://schema.org',
            '@type'     => 'Event',
            'name'      => 'Penerimaan Peserta Didik Baru (PPDB) ' . ($settings['school_name'] ?? ''),
            'startDate' => $settings['ppdb_start_date'],
            'endDate'   => $settings['ppdb_end_date'],
            'organizer' => [
                '@type' => 'Organization',
                'name'  => $settings['school_name'] ?? '',
            ],
        ];
    }

    /**
     * Schema FAQPage untuk halaman dengan seksi FAQ.
     * Return null jika tidak ada FAQ aktif.
     */
    public function faqPage(array $faqs): ?array
    {
        if (empty($faqs)) {
            return null;
        }

        $entities = array_map(fn($faq) => [
            '@type'          => 'Question',
            'name'           => $faq['question'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['answer']],
        ], $faqs);

        return [
            '@context'   => 'https://schema.org',
            '@type'      => 'FAQPage',
            'mainEntity' => $entities,
        ];
    }
}
```

### 7.4 CacheService

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    /**
     * Standard cache remember.
     */
    public function remember(string $key, int $ttl, callable $callback): mixed
    {
        return Cache::remember($key, $ttl, $callback);
    }

    /**
     * Stale-while-revalidate: sajikan stale jika ada, regenerasi di background (via afterResponse).
     */
    public function rememberStale(string $key, int $ttl, callable $callback): mixed
    {
        $staleKey    = "stale_{$key}";
        $gracePeriod = 300;

        // Cache fresh masih ada
        if (($value = Cache::get($key)) !== null) {
            return $value;
        }

        // Cache fresh expired, tapi stale masih ada
        if (($staleValue = Cache::get($staleKey)) !== null) {
            // Regenerasi di background setelah response dikirim
            dispatch(function () use ($key, $staleKey, $ttl, $gracePeriod, $callback) {
                $fresh = $callback();
                Cache::put($key, $fresh, $ttl);
                Cache::put($staleKey, $fresh, $ttl + $gracePeriod);
            })->afterResponse();

            return $staleValue;
        }

        // Keduanya expired: eksekusi langsung
        try {
            $fresh = $callback();
            Cache::put($key, $fresh, $ttl);
            Cache::put($staleKey, $fresh, $ttl + $gracePeriod);
            return $fresh;
        } catch (\Exception $e) {
            // Jika cache error, eksekusi callback langsung tanpa cache
            report($e);
            return $callback();
        }
    }

    /**
     * Hapus satu cache key.
     */
    public function forget(string $key): void
    {
        Cache::forget($key);
        Cache::forget("stale_{$key}");
    }

    /**
     * Hapus multiple cache keys berdasarkan array tag.
     */
    public function forgetByKeys(array $keys): void
    {
        foreach ($keys as $key) {
            $this->forget($key);
        }
    }
}
```

**Catatan:** Laravel file cache driver tidak mendukung cache tags. `forgetByKeys()` digunakan sebagai pengganti tag-based invalidation.

### 7.5 SitemapService

```php
<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Announcement;
use Illuminate\Support\Facades\Cache;

class SitemapService
{
    private array $staticUrls = [
        ['url' => '/',                  'priority' => '1.0', 'changefreq' => 'daily'],
        ['url' => '/profil/sejarah',    'priority' => '0.8', 'changefreq' => 'monthly'],
        ['url' => '/profil/visi-misi',  'priority' => '0.8', 'changefreq' => 'monthly'],
        ['url' => '/profil/pendidik',   'priority' => '0.7', 'changefreq' => 'monthly'],
        ['url' => '/kelulusan',         'priority' => '0.7', 'changefreq' => 'yearly'],
        ['url' => '/alumni',            'priority' => '0.6', 'changefreq' => 'monthly'],
        ['url' => '/ppdb',              'priority' => '0.9', 'changefreq' => 'weekly'],
    ];

    public function generate(): string
    {
        return Cache::remember('sitemap_xml', 60, function () {
            $urls = $this->staticUrls;

            // Berita published
            Post::published()->select('slug', 'updated_at')->get()
                ->each(fn($post) => $urls[] = [
                    'url'        => '/berita/' . $post->slug,
                    'priority'   => '0.6',
                    'changefreq' => 'weekly',
                    'lastmod'    => $post->updated_at->toAtomString(),
                ]);

            // Pengumuman published
            Announcement::published()->select('slug', 'updated_at')->get()
                ->each(fn($ann) => $urls[] = [
                    'url'        => '/pengumuman/' . $ann->slug,
                    'priority'   => '0.5',
                    'changefreq' => 'monthly',
                    'lastmod'    => $ann->updated_at->toAtomString(),
                ]);

            return $this->buildXml($urls);
        });
    }

    private function buildXml(array $urls): string
    {
        $baseUrl = config('app.url');
        $xml     = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml    .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        foreach ($urls as $entry) {
            $xml .= "  <url>" . PHP_EOL;
            $xml .= "    <loc>" . htmlspecialchars($baseUrl . $entry['url']) . "</loc>" . PHP_EOL;
            if (!empty($entry['lastmod'])) {
                $xml .= "    <lastmod>" . $entry['lastmod'] . "</lastmod>" . PHP_EOL;
            }
            $xml .= "    <changefreq>" . $entry['changefreq'] . "</changefreq>" . PHP_EOL;
            $xml .= "    <priority>" . $entry['priority'] . "</priority>" . PHP_EOL;
            $xml .= "  </url>" . PHP_EOL;
        }

        $xml .= '</urlset>';
        return $xml;
    }
}
```

---

## 8. Migration Files Order

Urutan migration harus mengikuti dependency foreign key:

```
database/migrations/
├── 0001_01_01_000000_create_users_table.php
├── 2025_01_01_000001_create_settings_table.php
├── 2025_01_01_000002_create_pages_table.php
├── 2025_01_01_000003_create_teachers_table.php
├── 2025_01_01_000004_create_posts_table.php          -- FK: users.id
├── 2025_01_01_000005_create_announcements_table.php
├── 2025_01_01_000006_create_graduations_table.php
├── 2025_01_01_000007_create_alumni_table.php
├── 2025_01_01_000008_create_tracer_studies_table.php -- FK: alumni.id
├── 2025_01_01_000009_create_ppdb_registrations_table.php
└── 2025_01_01_000010_create_faqs_table.php
```

### Contoh Migration `create_posts_table`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content')->nullable();
            $table->text('excerpt')->nullable();
            $table->string('thumbnail')->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->string('meta_title', 60)->nullable();
            $table->string('meta_description', 160)->nullable();
            $table->foreignId('author_id')->constrained('users')->restrictOnDelete();
            $table->timestamp('published_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('status');
            $table->index('published_at');
            $table->index(['status', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
```

---

## 9. Seeders

### 9.1 AdminUserSeeder

```php
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@smkmudabawean.sch.id'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('Admin@12345!'),  // Harus diganti saat first login
                'role'     => 'admin',
            ]
        );
    }
}
```

### 9.2 SettingSeeder

```php
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
```

### 9.3 DatabaseSeeder

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
```

---

## 10. Model Definitions

### Model `Post` (contoh lengkap dengan scope dan relasi)

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'content', 'excerpt', 'thumbnail',
        'status', 'meta_title', 'meta_description', 'author_id', 'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    // Scope: hanya post published
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    // Relasi ke user
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
```

### Model `Setting` (helper static)

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Ambil nilai setting, dengan fallback default.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $settings = Cache::remember('settings_all', 3600, function () {
            return static::all()->pluck('value', 'key')->toArray();
        });

        return $settings[$key] ?? $default;
    }

    /**
     * Set nilai setting.
     */
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget('settings_all');
        Cache::forget('theme_colors');
    }
}
```

---

## 11. Form Validation Rules

### 11.1 `StorePpdbRequest`

```php
public function rules(): array
{
    return [
        'full_name'    => ['required', 'string', 'max:100'],
        'birth_place'  => ['required', 'string', 'max:50'],
        'birth_date'   => ['required', 'date_format:Y-m-d', 'before:today'],
        'school_origin'=> ['required', 'string', 'max:100'],
        'parent_name'  => ['required', 'string', 'max:100'],
        'phone'        => ['required', 'regex:/^[0-9]{10,13}$/'],
    ];
}
```

### 11.2 `StoreAlumniRequest`

```php
public function rules(): array
{
    return [
        'full_name'       => ['required', 'string', 'max:100'],
        'graduation_year' => ['required', 'integer', 'min:1990', 'max:' . date('Y')],
        'email'           => ['required', 'email:rfc', 'max:255', 'unique:alumni,email'],
        'phone'           => ['nullable', 'string', 'max:20'],
        'address'         => ['nullable', 'string'],
    ];
}
```

### 11.3 `StoreTracerStudyRequest`

```php
public function rules(): array
{
    return [
        'full_name'        => ['required', 'string', 'max:100'],
        'graduation_year'  => ['required', 'integer', 'min:1990', 'max:' . date('Y')],
        'education_status' => ['required', 'string', 'max:100'],
        'employment_status'=> ['required', 'string', 'max:100'],
        'employer_name'    => ['nullable', 'string', 'max:150'],
        'position'         => ['nullable', 'string', 'max:100'],
    ];
}
```

### 11.4 `StorePostRequest`

```php
public function rules(): array
{
    return [
        'title'            => ['required', 'string', 'max:255'],
        'content'          => ['required', 'string'],
        'status'           => ['required', 'in:draft,published'],
        'thumbnail'        => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        'meta_title'       => ['nullable', 'string', 'max:60'],
        'meta_description' => ['nullable', 'string', 'max:160'],
        'published_at'     => ['nullable', 'date'],
    ];
}
```

### 11.5 `StoreSettingRequest` (tema warna)

```php
public function rules(): array
{
    return [
        'color_primary'   => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        'color_secondary' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        'color_accent'    => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
    ];
}
```

---

## 12. Error Handling

### HTTP 404 — Konten Tidak Ditemukan

`abort(404)` atau `Model::findOrFail()` / `Model::firstOrFail()` secara otomatis melempar `ModelNotFoundException` yang ditangani Laravel menjadi 404.

View: `resources/views/errors/404.blade.php` — extends `layouts/public`.

### HTTP 410 — Berita Dihapus (Soft Delete)

`NewsController@show` harus menangani soft-deleted post secara eksplisit:

```php
public function show(string $slug): View|Response
{
    // Cek apakah slug ada di post yang soft-deleted
    $deleted = Post::onlyTrashed()->where('slug', $slug)->exists();
    if ($deleted) {
        abort(410);
    }

    $post = Post::published()->where('slug', $slug)->firstOrFail();
    // ...
}
```

View: `resources/views/errors/410.blade.php`.

### HTTP 403 — Akses Ditolak

`abort(403)` di middleware `RoleCheck`.

View: `resources/views/errors/403.blade.php`.

### Cache Read Error

`CacheService::rememberStale()` membungkus eksekusi dengan try-catch. Jika cache tidak dapat dibaca, fallback ke eksekusi callback langsung.

---

## 13. Composer Dependencies

### Wajib

```json
{
    "require": {
        "php": "^8.2",
        "laravel/framework": "^11.0",
        "ezyang/htmlpurifier": "^4.17"
    }
}
```

### Opsional (jika dibutuhkan)

```json
{
    "require": {
        "league/csv": "^9.0"
    }
}
```

### Dev Dependencies

```json
{
    "require-dev": {
        "phpunit/phpunit": "^11.0",
        "laravel/pint": "^1.0",
        "fakerphp/faker": "^1.23"
    }
}
```

**Catatan:** Tidak menggunakan package berat seperti `spatie/laravel-permission` (diganti middleware custom ringan) atau `livewire` (diganti vanilla JS). Sesuai target shared hosting.

---

## 14. Testing Strategy

### 14.1 Test Structure

```
tests/
├── Unit/
│   ├── Services/
│   │   ├── SlugServiceTest.php
│   │   ├── HtmlSanitizerServiceTest.php
│   │   ├── SchemaMarkupServiceTest.php
│   │   └── ThemeServiceTest.php
│   └── Models/
│       └── SettingTest.php
├── Feature/
│   ├── Public/
│   │   ├── HomePageTest.php
│   │   ├── NewsPageTest.php
│   │   ├── AnnouncementPageTest.php
│   │   ├── GraduationPageTest.php
│   │   ├── AlumniPageTest.php
│   │   ├── PpdbPageTest.php
│   │   └── ProfilePageTest.php
│   ├── Admin/
│   │   ├── AuthTest.php
│   │   ├── PostCrudTest.php
│   │   ├── AnnouncementCrudTest.php
│   │   ├── GraduationImportTest.php
│   │   ├── AlumniManagementTest.php
│   │   ├── PpdbManagementTest.php
│   │   └── SettingsTest.php
│   └── Property/
│       ├── SlugPropertyTest.php          -- P1, P2
│       ├── HtmlSanitizerPropertyTest.php -- P3, P4
│       ├── PpdbRegistrationPropertyTest.php -- P5
│       ├── SchemaMarkupPropertyTest.php  -- P6
│       ├── ThemeColorPropertyTest.php    -- P7
│       ├── CsvRoundTripPropertyTest.php  -- P8
│       ├── PaginationPropertyTest.php    -- P9
│       └── CacheInvalidationPropertyTest.php -- P10
```

### 14.2 Unit Tests

#### `SlugServiceTest`

```php
public function test_slugify_lowercase(): void
{
    $service = new SlugService();
    $slug = $service->generate('Hello World', Post::class);
    $this->assertSame('hello-world', $slug);
}

public function test_slug_uniqueness_appends_counter(): void
{
    Post::factory()->create(['slug' => 'test-berita']);
    $service = new SlugService();
    $slug = $service->generate('Test Berita', Post::class);
    $this->assertSame('test-berita-1', $slug);
}
```

#### `HtmlSanitizerServiceTest`

```php
public function test_strips_script_tags(): void
{
    $sanitizer = new HtmlSanitizerService();
    $output = $sanitizer->clean('<p>Hello</p><script>alert("xss")</script>');
    $this->assertStringNotContainsString('<script>', $output);
    $this->assertStringContainsString('Hello', $output);
}

public function test_strips_event_handlers(): void
{
    $sanitizer = new HtmlSanitizerService();
    $output = $sanitizer->clean('<p onclick="evil()">Text</p>');
    $this->assertStringNotContainsString('onclick', $output);
}

public function test_preserves_allowed_tags(): void
{
    $sanitizer = new HtmlSanitizerService();
    $input = '<p><strong>Bold</strong> and <em>italic</em></p>';
    $output = $sanitizer->clean($input);
    $this->assertStringContainsString('<strong>Bold</strong>', $output);
    $this->assertStringContainsString('<em>italic</em>', $output);
}
```

### 14.3 Feature Tests

#### Contoh `HomePageTest`

```php
public function test_home_page_returns_200(): void
{
    $this->get('/')->assertStatus(200);
}

public function test_home_page_shows_latest_6_posts(): void
{
    Post::factory()->count(8)->published()->create();
    $response = $this->get('/');
    $response->assertViewHas('posts', fn($posts) => $posts->count() === 6);
}

public function test_home_page_shows_empty_message_when_no_posts(): void
{
    $this->get('/')->assertSee('Belum ada berita');
}
```

#### Contoh `AuthTest`

```php
public function test_login_locks_account_after_5_failed_attempts(): void
{
    $user = User::factory()->create(['password' => Hash::make('correct')]);

    for ($i = 0; $i < 5; $i++) {
        $this->post('/admin/login', ['email' => $user->email, 'password' => 'wrong']);
    }

    $user->refresh();
    $this->assertNotNull($user->locked_until);
    $this->assertTrue(now()->lt($user->locked_until));
}

public function test_unauthenticated_redirected_to_login(): void
{
    $this->get('/admin/dashboard')->assertRedirect('/admin/login');
}

public function test_editor_cannot_access_settings(): void
{
    $editor = User::factory()->create(['role' => 'editor']);
    $this->actingAs($editor)->get('/admin/settings')->assertStatus(403);
}
```

### 14.4 Property-Based Tests (P1–P10)

Karena tidak ada library PBT native di PHP yang semapan QuickCheck, property tests diimplementasikan dengan loop manual menggunakan PHPUnit data provider dan random data generation:

#### P1 & P2: Slug Idempotence & Valid Characters (`SlugPropertyTest`)

```php
/**
 * P1: generate_slug(title) == generate_slug(generate_slug(title))
 * P2: slug hanya mengandung [a-z0-9\-] dan tidak dimulai/diakhiri '-'
 */
public function test_slug_idempotence_and_valid_chars(): void
{
    $service = new SlugService();
    $titles = [
        'Hello World', 'Berita Terbaru 2025', '  spaces  ',
        'Special!@#$%Characters', 'Judul dengan HURUF KAPITAL',
        'a', str_repeat('a', 255),
    ];

    // Random titles
    for ($i = 0; $i < 100; $i++) {
        $titles[] = $this->randomTitle();
    }

    foreach ($titles as $title) {
        if (strlen($title) < 1 || strlen($title) > 255) continue;

        $slug1 = $service->generate($title, Post::class);
        $slug2 = $service->generate($slug1, Post::class);

        // P1: idempotence
        $this->assertSame($slug1, $slug2, "Slug tidak idempoten untuk title: {$title}");

        // P2: karakter valid
        $this->assertMatchesRegularExpression(
            '/^[a-z0-9][a-z0-9\-]*[a-z0-9]$|^[a-z0-9]$/',
            $slug1,
            "Slug mengandung karakter invalid: {$slug1}"
        );
    }
}

private function randomTitle(): string
{
    $chars = 'abcdefghijklmnopqrstuvwxyz ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%';
    $len = random_int(1, 100);
    $title = '';
    for ($i = 0; $i < $len; $i++) {
        $title .= $chars[random_int(0, strlen($chars) - 1)];
    }
    return $title;
}
```

#### P3 & P4: HTML Sanitizer (`HtmlSanitizerPropertyTest`)

```php
/**
 * P3: input dengan tag berbahaya → output tidak mengandung tag tersebut
 * P4: input dengan hanya tag diizinkan → output mempertahankan konten
 */
public function test_sanitizer_safety_invariant(): void
{
    $sanitizer = new HtmlSanitizerService();
    $dangerous = ['<script>', '</script>', '<iframe', '<object', '<embed', 'onclick=', 'onerror=', 'onload='];

    $maliciousInputs = [
        '<script>alert("xss")</script><p>text</p>',
        '<iframe src="evil.com"></iframe>',
        '<p onclick="evil()">text</p>',
        '<img src="x" onerror="alert(1)">',
        '<object data="evil"></object>',
    ];

    // P3
    foreach ($maliciousInputs as $input) {
        $output = $sanitizer->clean($input);
        foreach ($dangerous as $tag) {
            $this->assertStringNotContainsStringIgnoringCase($tag, $output,
                "Output mengandung string berbahaya '{$tag}' dari input: {$input}");
        }
    }
}

public function test_sanitizer_preserves_allowed_content(): void
{
    $sanitizer = new HtmlSanitizerService();
    $allowedInputs = [
        '<p>Paragraf biasa</p>',
        '<p><strong>Bold</strong> dan <em>italic</em></p>',
        '<ul><li>Item 1</li><li>Item 2</li></ul>',
        '<h2>Heading dua</h2>',
    ];

    foreach ($allowedInputs as $input) {
        $output = $sanitizer->clean($input);
        // P4: teks konten harus tetap ada
        $textContent = strip_tags($input);
        $this->assertStringContainsString($textContent, strip_tags($output));
    }
}
```

#### P5: PPDB Registration Number Uniqueness (`PpdbRegistrationPropertyTest`)

```php
/**
 * P5: N pendaftaran berhasil → jumlah nomor registrasi unik = N
 */
public function test_ppdb_registration_numbers_are_unique(): void
{
    $service = new PpdbService();
    $numbers = [];
    $n = 20;

    for ($i = 0; $i < $n; $i++) {
        $numbers[] = $service->generateRegistrationNumber();
        // Insert ke DB untuk pastikan uniqueness check bekerja
        PpdbRegistration::factory()->create(['registration_number' => end($numbers)]);
    }

    $unique = array_unique($numbers);
    $this->assertCount($n, $unique, 'Ditemukan nomor registrasi duplikat');
}
```

#### P6: Schema Markup Valid JSON (`SchemaMarkupPropertyTest`)

```php
/**
 * P6: output JSON-LD dapat di-parse dan round-trip konsisten
 */
public function test_schema_markup_is_valid_json(): void
{
    $service = new SchemaMarkupService();
    $posts = Post::factory()->count(10)->published()->create();

    foreach ($posts as $post) {
        $schema = $service->newsArticle($post);
        if ($schema === null) continue;

        $json = json_encode($schema, JSON_UNESCAPED_UNICODE);
        $this->assertNotFalse($json, 'json_encode gagal');

        $decoded = json_decode($json, true);
        $this->assertNotNull($decoded, 'json_decode gagal');
        $this->assertEquals($schema, $decoded, 'Round-trip JSON tidak konsisten');
    }
}
```

#### P7: Theme Color CSS Variable Consistency (`ThemeColorPropertyTest`)

```php
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
```

#### P8: CSV Round-Trip (`CsvRoundTripPropertyTest`)

```php
/**
 * P8: import CSV → export CSV → nilai identik dengan file asal
 */
public function test_graduation_csv_roundtrip(): void
{
    $original = [
        ['nama_siswa','nomor_peserta','program_keahlian','status_kelulusan'],
        ['Ahmad Fauzi','2024-001','Teknik Komputer Jaringan','LULUS'],
        ['Siti Aminah','2024-002','Akuntansi','TIDAK LULUS'],
    ];

    // Buat file CSV sementara
    $tmpFile = tempnam(sys_get_temp_dir(), 'csv_test_');
    $handle = fopen($tmpFile, 'w');
    foreach ($original as $row) fputcsv($handle, $row);
    fclose($handle);

    // Import
    $csvService = new CsvService();
    $result = $csvService->importGraduations(new UploadedFile($tmpFile, 'test.csv', 'text/csv', null, true), '2024/2025');

    $this->assertEquals(2, $result['imported']);
    $this->assertEquals(0, $result['failed']);

    // Export
    $exportedCsv = $csvService->exportGraduations('2024/2025');
    $rows = str_getcsv($exportedCsv, "\n");

    // Bandingkan baris data (skip header)
    $this->assertStringContainsString('Ahmad Fauzi', $exportedCsv);
    $this->assertStringContainsString('LULUS', $exportedCsv);
    $this->assertStringContainsString('TIDAK LULUS', $exportedCsv);

    unlink($tmpFile);
}
```

#### P9: Pagination Completeness (`PaginationPropertyTest`)

```php
/**
 * P9: sum item di semua halaman = N, tidak ada duplikat
 */
public function test_pagination_completeness(): void
{
    $n = 23;
    $pageSize = 10;
    Post::factory()->count($n)->published()->create();

    $totalPages = (int) ceil($n / $pageSize);
    $allIds = [];

    for ($page = 1; $page <= $totalPages; $page++) {
        $response = $this->get("/berita?page={$page}");
        $response->assertStatus(200);
        $posts = $response->viewData('posts');
        foreach ($posts as $post) {
            $allIds[] = $post->id;
        }
    }

    $this->assertCount($n, $allIds, 'Total item di semua halaman tidak sama dengan N');
    $this->assertCount($n, array_unique($allIds), 'Ada item duplikat di halaman berbeda');
}
```

#### P10: Cache Invalidation Consistency (`CacheInvalidationPropertyTest`)

```php
/**
 * P10: setelah konten diupdate dan cache diinvalidasi (≤5 detik),
 *      request berikutnya mengembalikan konten terbaru
 */
public function test_cache_invalidation_consistency(): void
{
    $post = Post::factory()->published()->create(['title' => 'Judul Lama']);

    // Panaskan cache
    $this->get('/')->assertSee('Judul Lama');

    // Update konten
    $startTime = microtime(true);
    $post->update(['title' => 'Judul Baru']);
    // Observer otomatis invalidasi cache setelah update

    $elapsed = microtime(true) - $startTime;
    $this->assertLessThan(5, $elapsed, 'Invalidasi cache melebihi 5 detik');

    // Request berikutnya harus mengembalikan konten baru
    $this->get('/')->assertSee('Judul Baru');
    $this->get('/')->assertDontSee('Judul Lama');
}
```

---

## 15. Accessibility Implementation

### 15.1 Semantic HTML Requirements

Setiap halaman publik wajib menggunakan landmark elements berikut:

```html
<body>
  <a href="#main-content" class="sr-only focus:not-sr-only">Lewati navigasi</a>
  <header role="banner">...</header>
  <nav role="navigation" aria-label="Navigasi utama">...</nav>
  <main id="main-content" role="main" tabindex="-1">
    <article>...</article>  <!-- untuk halaman detail konten -->
  </main>
  <footer role="contentinfo">...</footer>
</body>
```

### 15.2 Image Alt Text

- Gambar informatif: `alt="[deskripsi konten gambar]"` — wajib diisi.
- Gambar dekoratif: `alt=""` — dikosongkan.
- Thumbnail berita: `alt="{{ $post->title }}"`.
- Foto pendidik: `alt="Foto {{ $teacher->name }}, {{ $teacher->position }}"`.
- Placeholder foto: `alt="Foto profil {{ $teacher->name }} belum tersedia"`.

### 15.3 Form Accessibility

Setiap input formulir harus memiliki `<label>` yang terhubung:

```html
<div>
  <label for="full_name">Nama Lengkap <span aria-hidden="true">*</span></label>
  <input type="text" id="full_name" name="full_name"
         aria-required="true"
         aria-describedby="full_name_error"
         value="{{ old('full_name') }}">
  @error('full_name')
  <p id="full_name_error" role="alert" class="text-red-600">{{ $message }}</p>
  @enderror
</div>
```

### 15.4 Heading Hierarchy

Setiap halaman harus mengikuti urutan heading yang benar:

```
<h1> — Judul halaman (hanya satu per halaman)
  <h2> — Section utama
    <h3> — Sub-section
```

Contoh untuk halaman Beranda:

```html
<h1>SMK Muda Bawean</h1>
<section>
  <h2>Berita Terbaru</h2>
  <article><h3>Judul Berita</h3></article>
</section>
<section>
  <h2>Pengumuman</h2>
</section>
```

---

## 16. Performance Optimizations

### 16.1 Database Query Limits

Halaman sederhana (≤10 query):
- Home: 1 query settings + 1 query posts + 1 query announcements = 3 query (dengan eager loading)
- Detail Berita: 1 query post (with author) = 1 query
- Halaman Profil: 1 query page content + 1 query settings = 2 query

Halaman kompleks (≤20 query):
- Kelulusan dengan pencarian: 1 query graduation + 1 query academic years = 2 query
- Admin Dashboard: statistik 4 tabel = 4 query COUNT

### 16.2 Eager Loading

Selalu gunakan eager loading untuk menghindari N+1:

```php
// NewsController@index — bukan:
Post::published()->get()->each(fn($p) => $p->author->name);

// Tapi:
Post::published()->with('author:id,name')->latest('published_at')->paginate(10);
```

### 16.3 Static Asset Caching

Di `.htaccess` (Apache):

```apache
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/webp "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/png  "access plus 1 year"
    ExpiresByType text/css   "access plus 1 year"
    ExpiresByType application/javascript "access plus 1 year"
</IfModule>

<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/css application/javascript
</IfModule>
```

Untuk aset CSS/JS yang di-fingerprint (via `vite` atau manual hash di nama file), Laravel secara otomatis menambah query string atau hash di nama file.

### 16.4 Lazy Loading Gambar

Semua `<img>` yang tidak berada di above-the-fold menggunakan:

```html
<img src="{{ $imageSrc }}" alt="{{ $imageAlt }}" loading="lazy" width="400" height="300">
```

Gambar di hero section (above-the-fold) menggunakan `loading="eager"` (default) atau `fetchpriority="high"`.

---

## 17. Environment Configuration

### `.env` Tambahan yang Diperlukan

```dotenv
# App
APP_NAME="SMK Muda Bawean"
APP_ENV=production
APP_KEY=base64:...
APP_DEBUG=false
APP_URL=https://smkmudabawean.sch.id

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smkmudabawean
DB_USERNAME=db_user
DB_PASSWORD=db_pass

# Cache (file driver — compatible dengan shared hosting)
CACHE_STORE=file

# Queue (sync — tanpa daemon)
QUEUE_CONNECTION=sync

# Session (file driver)
SESSION_DRIVER=file
SESSION_LIFETIME=60

# Mail (opsional untuk notifikasi)
MAIL_MAILER=smtp
```

---

## 18. Deployment Checklist

Langkah-langkah deploy ke shared hosting:

1. Upload seluruh kode (kecuali `node_modules`, `.git`, `storage/logs`) ke server.
2. Set `public/` sebagai document root, atau upload isi `public/` ke `public_html/` dan sesuaikan `index.php`.
3. Set permission: `storage/` dan `bootstrap/cache/` → `755` (atau `775` jika group web server).
4. Jalankan: `composer install --no-dev --optimize-autoloader`
5. Set environment variables di `.env`.
6. Jalankan: `php artisan key:generate`
7. Jalankan: `php artisan migrate --force`
8. Jalankan: `php artisan db:seed --force`
9. Jalankan: `php artisan storage:link`
10. Jalankan: `php artisan config:cache && php artisan route:cache && php artisan view:cache`
11. Pastikan `APP_DEBUG=false` di production.
12. Buat direktori `storage/app/htmlpurifier/` dan beri permission write.

---

## 19. Correctness Properties Summary

| ID | Property | Test File |
|----|----------|-----------|
| P1 | Slug Generation — Idempotence | `SlugPropertyTest` |
| P2 | Slug Generation — Valid Characters | `SlugPropertyTest` |
| P3 | HTML Sanitization — Safety Invariant | `HtmlSanitizerPropertyTest` |
| P4 | HTML Sanitization — Content Preservation | `HtmlSanitizerPropertyTest` |
| P5 | PPDB Registration — Uniqueness | `PpdbRegistrationPropertyTest` |
| P6 | Schema Markup — Valid JSON | `SchemaMarkupPropertyTest` |
| P7 | Theme Color — CSS Variable Consistency | `ThemeColorPropertyTest` |
| P8 | CSV Import/Export — Round Trip | `CsvRoundTripPropertyTest` |
| P9 | Pagination — Completeness | `PaginationPropertyTest` |
| P10 | Cache Invalidation — Consistency | `CacheInvalidationPropertyTest` |

Semua property test terletak di `tests/Feature/Property/` dan dijalankan dengan:

```bash
php artisan test --filter=PropertyTest
# atau
./vendor/bin/phpunit tests/Feature/Property/
```
