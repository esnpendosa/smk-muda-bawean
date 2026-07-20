<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Announcement;
use App\Models\Setting;
use App\Services\CacheService;

class HomeController extends Controller
{
    protected CacheService $cacheService;

    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Display the school homepage.
     */
    public function index()
    {
        $data = $this->cacheService->rememberStale('home_page', 3600, function () {
            return [
                'posts' => Post::published()->with('author')->orderBy('published_at', 'desc')->take(6)->get(),
                'announcements' => Announcement::published()->orderBy('published_at', 'desc')->take(5)->get(),
            ];
        });

        $posts = $data['posts'];
        $announcements = $data['announcements'];
        $principalGreeting = Setting::get('principal_greeting', 'Selamat Datang di SMK Muda Bawean');
        $principalPhoto = Setting::get('principal_photo');

        $seo = [
            'title' => 'Beranda',
            'description' => Setting::get('school_description', 'Website Resmi SMK Muda Bawean')
        ];

        return view('public.home.index', compact('posts', 'announcements', 'principalGreeting', 'principalPhoto', 'seo'));
    }
}
