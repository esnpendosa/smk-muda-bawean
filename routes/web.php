<?php

use Illuminate\Support\Facades\Route;
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
Route::post('/kelulusan/search', [GraduationController::class, 'search'])->name('kelulusan.search');
Route::get('/kelulusan/{nisn}/download', [GraduationController::class, 'download'])->name('kelulusan.download');

// Alumni & Tracer Study
Route::prefix('alumni')->name('alumni.')->group(function () {
    Route::get('/',                     [AlumniController::class, 'index'])->name('index');
    Route::post('/',                    [AlumniController::class, 'store'])->name('store');
    Route::get('/tracer-study',         [AlumniController::class, 'tracerStudy'])->name('tracer-study');
    Route::post('/tracer-study',        [AlumniController::class, 'storeTracerStudy'])->name('tracer-study.store');
});

use App\Http\Controllers\Public\FaqController;

// PPDB
Route::prefix('ppdb')->name('ppdb.')->group(function () {
    Route::get('/',   [PpdbController::class, 'index'])->name('index');
    Route::post('/',  [PpdbController::class, 'store'])->name('store');
});

// FAQ
Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');

// SEO
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt',  [SitemapController::class, 'robots'])->name('robots');
