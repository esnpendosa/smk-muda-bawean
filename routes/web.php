<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\NewsController;
use App\Http\Controllers\Public\CommentController;
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
    Route::get('/',                 [NewsController::class, 'index'])->name('index');
    Route::get('/{slug}',           [NewsController::class, 'show'])->name('show');
    Route::post('/{slug}/comments', [CommentController::class, 'store'])->name('comments.store');
});

// Comment Upvote
Route::post('/comments/{id}/upvote', [CommentController::class, 'upvote'])->name('comments.upvote');

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

// Utility route to fix storage symlink on shared hosting
Route::get('/fix-storage', function () {
    $storageLink = public_path('storage');
    $msg = '';

    if (file_exists($storageLink) || is_link($storageLink)) {
        if (is_link($storageLink)) {
            if (unlink($storageLink)) {
                $msg .= "Deleted existing broken symlink. ";
            } else {
                $msg .= "Failed to delete existing symlink. ";
            }
        } else {
            $newName = $storageLink . '_bak_' . time();
            if (rename($storageLink, $newName)) {
                $msg .= "Renamed existing directory to " . basename($newName) . ". ";
            } else {
                $msg .= "Failed to rename existing directory. ";
            }
        }
    } else {
        $msg .= "No existing storage link/directory found. ";
    }

    try {
        app()->make('files')->link(
            storage_path('app/public'), public_path('storage')
        );
        return $msg . "Storage link created successfully!";
    } catch (\Exception $e) {
        return $msg . "Error creating storage link: " . $e->getMessage() . 
               ". If it failed, you can manually delete the 'public/storage' folder/link via your hosting file manager. The application will automatically use the fallback storage route to serve images.";
    }
});

// Fallback storage route to serve images/files dynamically if symlink is broken or disabled
Route::get('/storage/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    
    if (!file_exists($fullPath) || is_dir($fullPath)) {
        abort(404);
    }
    
    $file = file_get_contents($fullPath);
    $type = mime_content_type($fullPath);
    
    return response($file, 200)->header("Content-Type", $type);
})->where('path', '.*');
