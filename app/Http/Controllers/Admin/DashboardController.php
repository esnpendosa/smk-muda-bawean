<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Announcement;
use App\Models\PpdbRegistration;
use App\Models\Alumni;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'posts'         => Post::count(),
            'announcements' => Announcement::count(),
            'ppdb'          => PpdbRegistration::count(),
            'alumni'        => Alumni::count(),
        ];

        return view('admin.dashboard.index', compact('stats'));
    }
}
