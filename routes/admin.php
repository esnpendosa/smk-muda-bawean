<?php

use Illuminate\Support\Facades\Route;
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

Route::name('admin.')->group(function () {

    // Autentikasi (tidak memerlukan middleware auth)
    Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])
         ->middleware('rate.limit.login')
         ->name('login.submit');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Route yang memerlukan autentikasi
    Route::middleware(['admin.auth'])->group(function () {

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Berita
        Route::resource('posts', PostController::class);

        // Pengumuman
        Route::resource('announcements', AnnouncementController::class);

        // Kelulusan — export/import must be declared before resource to avoid wildcard conflicts
        Route::get('graduations/export',  [GraduationController::class, 'export'])->name('graduations.export');
        Route::post('graduations/import', [GraduationController::class, 'import'])->name('graduations.import');
        Route::resource('graduations', GraduationController::class);

        // Alumni & Tracer Study
        Route::resource('alumni', AlumniController::class);
        Route::get('tracer-studies', [AlumniController::class, 'tracerStudies'])->name('tracer-studies.index');

        // PPDB — export must be declared before resource to avoid wildcard conflicts
        Route::get('ppdb/export', [PpdbController::class, 'export'])->name('ppdb.export');
        Route::resource('ppdb', PpdbController::class)->only(['index', 'show', 'update']);

        // Pendidik
        Route::resource('teachers', TeacherController::class);

        // Halaman Statis
        Route::resource('pages', PageController::class)->only(['index', 'show', 'edit', 'update']);

        // FAQ
        Route::resource('faqs', FaqController::class);

        // Pengguna (hanya admin)
        Route::resource('users', UserController::class)->middleware('role:admin');

        // Pengaturan (hanya admin)
        Route::middleware('role:admin')->group(function () {
            Route::get('settings',         [SettingController::class, 'index'])->name('settings.index');
            Route::post('settings',        [SettingController::class, 'update'])->name('settings.update');
            Route::get('settings/seo',     [SettingController::class, 'seo'])->name('settings.seo');
            Route::post('settings/seo',    [SettingController::class, 'updateSeo'])->name('settings.seo.update');
            Route::get('settings/theme',   [SettingController::class, 'theme'])->name('settings.theme');
            Route::post('settings/theme',  [SettingController::class, 'updateTheme'])->name('settings.theme.update');
        });
    });
});
