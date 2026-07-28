# 📘 BUKU PANDUAN PENGGUNA & MANUAL TEKNIS
## Portal Informasi SMK Muhammadiyah 4 Sangkapura (SMK MUDA Bawean)

Dokumen ini berisi panduan lengkap untuk Administrator dan Tim Teknis dalam mengelola, mengembangkan, serta mendeploy website SMK MUDA Bawean.

---

## 📋 DAFTAR ISI
1. [Spesifikasi Teknis](#1-spesifikasi-teknis)
2. [Fitur Utama Sistem](#2-fitur-utama-sistem)
3. [Panduan Instalasi & Pengaturan Awal](#3-panduan-instalasi--pengaturan-awal)
4. [Panduan Menulis Berita (Lengkap + Gambar)](#4-panduan-menulis-berita-lengkap--gambar)
5. [Panduan Manajemen Konten & Database](#5-panduan-manajemen-konten--database)
6. [Sistem Komentar & Moderasi](#6-sistem-komentar--moderasi)
7. [Pengaturan Tampilan & Hero Slider](#7-pengaturan-tampilan--hero-slider)
8. [Panduan SEO & Favicon](#8-panduan-seo--favicon)
9. [Panduan Git & Alur Sinkronisasi GitHub](#9-panduan-git--alur-sinkronisasi-github)

---

## 1. SPESIFIKASI TEKNIS
* **Framework**: Laravel 10.x / 11.x (PHP 8.2+)
* **Database**: MySQL / MariaDB
* **CSS Framework**: Tailwind CSS (via Play CDN & theme-configured variables)
* **Editor Berita**: Summernote (WYSIWYG editor — mirip WordPress)
* **Design Theme**: Consistent Green-Emerald Color Scheme (Branding Resmi SMK)
* **SEO Engine**: Dynamic Schema Markup & Metadata Engine

---

## 2. FITUR UTAMA SISTEM
* **Landing Page Dinamis**: Banner Slider Interaktif, Berita Terbaru, Pengumuman, Agenda, Testimoni Alumni, dan Jadwal Operasional Pelayanan.
* **Portal PPDB Online**: Sistem Penerimaan Peserta Didik Baru terintegrasi secara dinamis.
* **Portal Alumni & Tracer Study**: Formulir penelusuran lulusan (Tracer Study) serta direktori Alumni.
* **Sistem Kelulusan Mandiri**: Fitur pencarian status kelulusan siswa menggunakan nomor peserta ujian dengan opsi cetak surat kelulusan PDF secara real-time.
* **International Media-Style Comment System**: Sistem komentar berulir (threaded), AJAX-driven, dilengkapi fitur voting (upvote) serta moderasi terintegrasi di Admin Panel.
* **Social Media Sharing Utility**: Tombol bagikan ke WhatsApp, Facebook, X (Twitter), dan opsi salin tautan artikel.
* **Editor Berita WYSIWYG**: Editor berita seperti WordPress — bisa format teks, sisipkan gambar, tabel, video, dan lain-lain.
* **Upload Gambar di Konten**: Gambar yang disisipkan di isi berita otomatis tersimpan di server dengan URL permanen.

---

## 3. PANDUAN INSTALASI & PENGATURAN AWAL

### Prasyarat
Pastikan server Anda sudah terinstal:
* PHP >= 8.2 (dengan ekstensi `pdo_mysql`, `mbstring`, `openssl`, `gd`)
* Composer
* MySQL Database

### Langkah Instalasi
1. **Clone repository dari GitHub**:
   ```bash
   git clone https://github.com/esnpendosa/smk-muda-bawean.git
   cd smk-muda-bawean
   ```

2. **Instal dependensi Composer**:
   ```bash
   composer install
   ```

3. **Salin file konfigurasi lingkungan (.env)**:
   ```bash
   cp .env.example .env
   ```

4. **Konfigurasi Database di file `.env`**:
   Sesuaikan baris berikut dengan database lokal atau production Anda:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=smkmudabawean
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```

6. **Jalankan Migrasi Database**:
   ```bash
   php artisan migrate
   ```

7. **Jalankan Seed Data Utama**:
   ```bash
   php artisan db:seed --class=AdminUserSeeder
   php artisan db:seed --class=SettingSeeder
   php artisan db:seed --class=PostSeeder
   ```

8. **Buat Tautan Storage**:
   ```bash
   php artisan storage:link
   ```

9. **Jalankan Server Lokal**:
   ```bash
   php artisan serve
   ```
   Akses website melalui browser di `http://127.0.0.1:8000`.

---

## 4. PANDUAN MENULIS BERITA (LENGKAP + GAMBAR)

Fitur penulisan berita telah ditingkatkan menjadi **editor WYSIWYG** (What You See Is What You Get) seperti WordPress, dilengkapi kemampuan upload gambar langsung ke server.

### 4.1 Cara Membuat Berita Baru

1. Login ke **Admin Panel**: `http://[domain-anda]/admin/login`
2. Klik menu **Berita** di sidebar kiri
3. Klik tombol **+ Tambah Berita**
4. Isi formulir berikut:

| Field | Keterangan |
|-------|-----------|
| **Judul** | Judul berita (wajib diisi) |
| **Konten** | Isi berita — bisa format teks, gambar, tabel, dll. |
| **Status** | `Draft` = tersimpan tapi tidak tampil | `Published` = tampil di website |
| **Tanggal Publikasi** | Atur tanggal & jam tayang berita |
| **Thumbnail** | Foto utama yang muncul di kartu berita (halaman daftar berita) |
| **Meta Title** | Judul untuk SEO (maks. 60 karakter) |
| **Meta Description** | Deskripsi untuk SEO & WhatsApp share (maks. 160 karakter) |

---

### 4.2 Cara Menyisipkan Gambar di Isi Berita

> ⚠️ **Penting**: Ada **dua jenis gambar** di sistem berita:
> - **Thumbnail** = foto kecil yang muncul di kartu/daftar berita (upload di field Thumbnail)
> - **Gambar di Konten** = foto yang muncul di dalam isi artikel (sisipkan lewat editor)

#### Langkah menyisipkan gambar di dalam isi berita:

1. Klik area editor konten (kotak teks besar dengan toolbar di atasnya)
2. Klik **ikon gambar** (🖼️) di toolbar editor — biasanya ada di baris toolbar bagian **Insert**
3. Pilih **"Insert Image"**
4. Klik **"Browse Server"** atau pilih tab **"Upload"**
5. Klik **"Choose File"** → pilih foto dari komputer Anda
6. Klik **"Upload"** atau **"Insert"**
7. Gambar otomatis tersimpan di server dan muncul di editor ✅
8. Anda bisa mengatur ukuran, posisi (kiri/tengah/kanan) gambar setelah disisipkan

> 💡 **Tips**: Gambar yang disisipkan di editor tersimpan secara permanen di folder `storage/uploads/content/` di server. Gambar tidak akan hilang meski berita diedit ulang.

---

### 4.3 Toolbar Editor — Panduan Fitur

Editor berita memiliki toolbar lengkap berikut:

| Tombol | Fungsi |
|--------|--------|
| **B** | Tebal (Bold) |
| **I** | Miring (Italic) |
| **U** | Garis bawah (Underline) |
| **Style** | Pilih gaya teks (Heading 1, Heading 2, Normal, dll.) |
| **Color** | Warna teks & background teks |
| **• –** | Daftar bullet / Daftar angka |
| **≡** | Rata kiri / tengah / kanan / justify |
| **Table** | Sisipkan tabel |
| **🔗** | Sisipkan tautan/link |
| **🖼️** | **Sisipkan gambar** (upload ke server) |
| **▶️** | Sisipkan video (YouTube, dll.) |
| **⛶** | Mode layar penuh |
| **< >** | Mode kode HTML |

---

### 4.4 Cara Upload Thumbnail Berita

Thumbnail adalah **foto kecil** yang tampil di kartu berita pada halaman daftar berita.

1. Scroll ke bawah di formulir berita, temukan field **"Thumbnail"**
2. Klik **"Choose File"** atau **"Pilih File"**
3. Pilih foto dari komputer (format: JPG, PNG, WEBP — maks. 2MB)
4. Klik **Simpan Berita**
5. Thumbnail akan muncul otomatis di halaman daftar berita ✅

> ⚠️ **Berita lama yang thumbnailnya tidak muncul**: Buka **Edit Berita** → upload ulang thumbnail → Simpan.

---

### 4.5 Tips Penulisan Berita yang Baik

- **Judul**: Singkat, jelas, dan mengandung kata kunci utama (maks. 70 kata)
- **Paragraf pertama**: Ringkasan berita — apa, siapa, kapan, di mana, mengapa
- **Gambar**: Selalu tambahkan minimal 1 foto di dalam konten untuk menarik pembaca
- **Thumbnail**: Wajib diisi agar kartu berita tidak tampil kosong
- **Meta Description**: Isi dengan ringkasan menarik, akan muncul saat dibagikan ke WhatsApp

---

## 5. PANDUAN MANAJEMEN KONTEN & DATABASE

### Migrasi Artikel Berita (PostSeeder)
Sistem memiliki 13 artikel berita historis bawaan dari WordPress yang telah dimigrasikan ke Laravel secara penuh menggunakan model Eloquent.

Untuk mempopulasikan kembali atau mengatur ulang artikel berita di database lokal/produksi, Anda dapat menggunakan perintah:
```bash
php artisan db:seed --class=PostSeeder
```
* **Asset Cover Gambar**: Semua thumbnail berita dipetakan ke direktori `public/images/` dengan format gambar yang rapi (`artikel-fortasi.png`, `artikel-ujian-digital.png`, dll.).
* **Pembersihan Otomatis**: Seeder secara otomatis menghapus record artikel lama sebelum menyisipkan data baru untuk mencegah duplikasi konten (`Post::query()->forceDelete()`).

---

## 6. SISTEM KOMENTAR & MODERASI

Website dilengkapi fitur diskusi interaktif bergaya media internasional dengan spesifikasi berikut:
* **Diskusi Berulir**: Mendukung balasan (replies) bersarang (nested) hingga **3 tingkat kedalaman** untuk percakapan yang rapi.
* **Tanpa Muat Ulang Halaman (AJAX)**: Pengiriman komentar dan balasan diproses secara asinkron menggunakan Vanilla Javascript & Fetch API demi pengalaman pengguna yang lancar.
* **Upvote System**: Pengunjung dapat memberikan dukungan (upvote) pada komentar yang bermanfaat secara instan tanpa perlu reload.
* **Moderasi Admin**: Komentar yang dikirim pengunjung akan berstatus `pending` secara default sebelum disetujui (Approved) oleh administrator.

### Cara Memoderasi Komentar (Admin Panel):
1. Masuk ke halaman Admin (`http://[domain-anda]/admin/login`).
2. Masukkan akun administrator Anda:
   * **Email**: `admin@smkmudabawean.sch.id`
   * **Password**: `Admin@12345!`
3. Masuk ke menu **Komentar** di bilah menu navigasi samping (Sidebar).
4. Anda akan melihat daftar komentar pending, spam, maupun yang disetujui.
5. Klik tombol **Setujui** (Approve) agar komentar tampil ke publik, atau **Spam/Hapus** untuk menyembunyikannya.

---

## 7. PENGATURAN TAMPILAN & HERO SLIDER

Website menggunakan skema warna **Hijau Emerald** yang konsisten di semua bagian untuk mewakili identitas institusi pendidikan Muhammadiyah.

### Manajemen Hero Slider Landing Page
Konten teks, tautan tombol, dan gambar latar belakang slider halaman depan dapat diubah secara dinamis melalui panel pengaturan:
1. Di halaman **Admin Panel**, navigasikan ke menu **Pengaturan (Settings) > Slider**.
2. Anda dapat mengubah:
   * Judul Slide (Slide Title)
   * Teks Highlight (yang dicetak dengan warna hijau aksen)
   * Deskripsi Singkat (Slide Description)
   * Tautan & Label Tombol Utama & Kedua
   * Gambar Latar Belakang (Slide Background)
3. Tekan tombol **Simpan Pengaturan** untuk langsung menerapkannya pada halaman utama.

---

## 8. PANDUAN SEO & FAVICON

Website dikonfigurasi agar optimal di hasil pencarian Google (SEO Friendly):

### Favicon Browser
* Favicon resmi SMK menggunakan file `logo-smk.png` (resolusi disesuaikan) yang dipanggil di tag `<head>` layout publik maupun admin.
* Manfaat: Memastikan logo SMK muncul di samping judul tab browser pengunjung serta terbaca dengan baik oleh robot perayap Google (Googlebot Favicon compliance).

### Pengaturan SEO Global
* Setiap halaman memuat komponen `@include('components.seo-head')` yang menghasilkan tag meta OpenGraph (untuk WhatsApp/Facebook sharing), Meta Title, Meta Description, serta JSON-LD Schema Markup secara dinamis untuk memudahkan Google memahami struktur data sekolah.

---

## 9. PANDUAN GIT & ALUR SINKRONISASI GITHUB

Proyek ini telah dikonfigurasi menggunakan kontrol versi Git dengan remote repositori yang mengarah ke:
`https://github.com/esnpendosa/smk-muda-bawean.git`

### Alur Update Kode & Sinkronisasi Server

Jika Anda melakukan perubahan kode secara lokal, jalankan perintah berikut secara berurutan:

1. **Periksa status perubahan**:
   ```bash
   git status
   ```

2. **Tambahkan file baru atau modifikasi**:
   ```bash
   git add .
   ```

3. **Buat Commit**:
   ```bash
   git commit -m "Deskripsi singkat perubahan Anda (contoh: refactor: perbaiki layout halaman berita)"
   ```

4. **Kirim perubahan ke GitHub**:
   ```bash
   git push origin master
   ```

5. **Terapkan pada Server Produksi (Live Website)**:
   Masuk ke SSH server hosting Anda, navigasikan ke direktori website, kemudian ketikkan perintah:
   ```bash
   git pull origin master
   php artisan migrate
   php artisan optimize:clear
   ```

---

## 10. STRUKTUR FOLDER PENTING

```
smk-muda-bawean/
├── app/
│   ├── Http/Controllers/Admin/
│   │   ├── PostController.php        ← Logika CRUD berita
│   │   ├── ImageUploadController.php ← Upload gambar dari editor
│   │   └── SettingController.php     ← Pengaturan website
│   ├── Models/Post.php               ← Model berita
│   └── Services/HtmlSanitizerService.php ← Filter keamanan konten HTML
├── resources/views/
│   ├── admin/posts/
│   │   ├── create.blade.php          ← Form tambah berita
│   │   └── edit.blade.php            ← Form edit berita
│   └── public/berita/
│       └── show.blade.php            ← Halaman detail berita (publik)
├── storage/app/public/
│   └── uploads/
│       ├── [thumbnail].jpg           ← Foto thumbnail berita
│       └── content/[gambar].jpg      ← Gambar di dalam isi berita
└── public/
    ├── images/                       ← Gambar statis (thumbnail seeder)
    └── storage/                      ← Symlink ke storage/app/public
```

---

## 11. TROUBLESHOOTING

### Gambar tidak muncul setelah upload
```bash
# Pastikan symlink storage sudah ada
php artisan storage:link

# Pastikan folder uploads ada
mkdir -p storage/app/public/uploads/content
```

### Halaman error setelah update kode
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
```

### Login admin tidak bisa masuk
```bash
# Reset password admin (ganti email sesuai akun Anda)
php artisan tinker
>>> App\Models\User::where('email','admin@smkmudabawean.sch.id')->update(['password' => bcrypt('passwordbaru')])
```

---

*Dokumentasi ini disusun oleh tim pengembang asisten AI Antigravity pada Juli 2026.*
*Terakhir diperbarui: 28 Juli 2026 — Penambahan panduan upload gambar di editor berita.*
