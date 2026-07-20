# 📘 BUKU PANDUAN PENGGUNA & MANUAL TEKNIS
## Portal Informasi SMK Muhammadiyah 4 Sangkapura (SMK MUDA Bawean)

Dokumen ini berisi panduan lengkap untuk Administrator dan Tim Teknis dalam mengelola, mengembangkan, serta mendeploy website SMK MUDA Bawean.

---

## 📋 DAFTAR ISI
1. [Spesifikasi Teknis](#1-spesifikasi-teknis)
2. [Fitur Utama Sistem](#2-fitur-utama-sistem)
3. [Panduan Instalasi & Pengaturan Awal](#3-panduan-instalasi--pengaturan-awal)
4. [Panduan Manajemen Konten & Database](#4-panduan-manajemen-konten--database)
5. [Sistem Komentar & Moderasi](#5-sistem-komentar--moderasi)
6. [Pengaturan Tampilan & Hero Slider](#6-pengaturan-tampilan--hero-slider)
7. [Panduan SEO & Favicon](#7-panduan-seo--favicon)
8. [Panduan Git & Alur Sinkronisasi GitHub](#8-panduan-git--alur-sinkronisasi-github)

---

## 1. SPESIFIKASI TEKNIS
* **Framework**: Laravel 10.x / 11.x (PHP 8.2+)
* **Database**: MySQL / MariaDB
* **CSS Framework**: Tailwind CSS (via Play CDN & theme-configured variables)
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

## 4. PANDUAN MANAJEMEN KONTEN & DATABASE

### Migrasi Artikel Berita (PostSeeder)
Sistem memiliki 13 artikel berita historis bawaan dari WordPress yang telah dimigrasikan ke Laravel secara penuh menggunakan model Eloquent.

Untuk mempopulasikan kembali atau mengatur ulang artikel berita di database lokal/produksi, Anda dapat menggunakan perintah:
```bash
php artisan db:seed --class=PostSeeder
```
* **Asset Cover Gambar**: Semua thumbnail berita dipetakan ke direktori `public/images/` dengan format gambar yang rapi (`artikel-fortasi.png`, `artikel-ujian-digital.png`, dll.).
* **Pembersihan Otomatis**: Seeder secara otomatis menghapus record artikel lama sebelum menyisipkan data baru untuk mencegah duplikasi konten (`Post::query()->forceDelete()`).

---

## 5. SISTEM KOMENTAR & MODERASI

Website dilengkapi fitur diskusi interaktif bergaya media internasional dengan spesifikasi berikut:
* **Diskusi Berulir**: Mendukung balasan (replies) bersarang (nested) hingga **3 tingkat kedalaman** untuk percakapan yang rapi.
* **Tanpa Muat Ulang Halaman (AJAX)**: Pengiriman komentar dan balasan diproses secara asinkron menggunakan Vanilla Javascript & Fetch API demi pengalaman pengguna yang lancar.
* **Upvote System**: Pengunjung dapat memberikan dukungan (upvote) pada komentar yang bermanfaat secara instan tanpa perlu reload.
* **Moderasi Admin**: Komentar yang dikirim pengunjung akan berstatus `pending` secara default sebelum disetujui (Approved) oleh administrator.

### Cara Memoderasi Komentar (Admin Panel):
1. Masuk ke halaman Admin (`http://127.0.0.1:8000/admin/login`).
2. Masukkan akun administrator Anda:
   * **Email**: `admin@smkmudabawean.sch.id`
   * **Password**: `Admin@12345!`
3. Masuk ke menu **Komentar** di bilah menu navigasi samping (Sidebar).
4. Anda akan melihat daftar komentar pending, spam, maupun yang disetujui.
5. Klik tombol **Setujui** (Approve) agar komentar tampil ke publik, atau **Spam/Hapus** untuk menyembunyikannya.

---

## 6. PENGATURAN TAMPILAN & HERO SLIDER

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

## 7. PANDUAN SEO & FAVICON

Website dikonfigurasi agar optimal di hasil pencarian Google (SEO Friendly):

### Favicon Browser
* Favicon resmi SMK menggunakan file `logo-smk.png` (resolusi disesuaikan) yang dipanggil di tag `<head>` layout publik maupun admin.
* Manfaat: Memastikan logo SMK muncul di samping judul tab browser pengunjung serta terbaca dengan baik oleh robot perayap Google (Googlebot Favicon compliance).

### Pengaturan SEO Global
* Setiap halaman memuat komponen `@include('components.seo-head')` yang menghasilkan tag meta OpenGraph (untuk WhatsApp/Facebook sharing), Meta Title, Meta Description, serta JSON-LD Schema Markup secara dinamis untuk memudahkan Google memahami struktur data sekolah.

---

## 8. PANDUAN GIT & ALUR SINKRONISASI GITHUB

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
   php artisan db:seed --class=PostSeeder # Jika ada data artikel baru yang ingin diperbarui
   php artisan optimize:clear
   ```

---
*Dokumentasi ini disusun oleh tim pengembang asisten AI Antigravity pada Juli 2026.*
