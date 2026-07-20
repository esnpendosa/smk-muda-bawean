<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

// Models
use App\Models\Post;
use App\Models\Announcement;
use App\Models\Page;
use App\Models\Teacher;
use App\Models\Setting;

// Observers
use App\Observers\PostObserver;
use App\Observers\AnnouncementObserver;
use App\Observers\PageObserver;
use App\Observers\TeacherObserver;
use App\Observers\SettingObserver;

// Services
use App\Services\SlugService;
use App\Services\HtmlSanitizerService;
use App\Services\ThemeService;
use App\Services\CacheService;
use App\Services\SchemaMarkupService;
use App\Services\SitemapService;
use App\Services\CsvService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SlugService::class, function ($app) {
            return new SlugService();
        });

        $this->app->singleton(HtmlSanitizerService::class, function ($app) {
            return new HtmlSanitizerService();
        });

        $this->app->singleton(ThemeService::class, function ($app) {
            return new ThemeService();
        });

        $this->app->singleton(CacheService::class, function ($app) {
            return new CacheService();
        });

        $this->app->singleton(SchemaMarkupService::class, function ($app) {
            return new SchemaMarkupService();
        });

        $this->app->singleton(SitemapService::class, function ($app) {
            return new SitemapService();
        });

        $this->app->singleton(CsvService::class, function ($app) {
            return new CsvService();
        });

        $this->app->singleton(\App\Services\PpdbService::class, function ($app) {
            return new \App\Services\PpdbService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Bind Observers
        Post::observe(PostObserver::class);
        Announcement::observe(AnnouncementObserver::class);
        Page::observe(PageObserver::class);
        Teacher::observe(TeacherObserver::class);
        Setting::observe(SettingObserver::class);

        // View Composer for public layout
        View::composer('layouts.public', function ($view) {
            $view->with('themeColors', app(ThemeService::class)->getColors());
            $view->with('schoolName', Setting::get('school_name', 'SMK Muda Bawean'));
        });
    }
}
