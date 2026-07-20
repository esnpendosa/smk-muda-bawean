<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Services\SchemaMarkupService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AnnouncementController extends Controller
{
    protected SchemaMarkupService $schemaService;

    public function __construct(SchemaMarkupService $schemaService)
    {
        $this->schemaService = $schemaService;
    }

    /**
     * Display a listing of announcements.
     */
    public function index()
    {
        $announcements = Announcement::published()->orderBy('published_at', 'desc')->paginate(15);
        
        $seo = [
            'title' => 'Pengumuman',
            'description' => 'Daftar pengumuman resmi dan agenda kegiatan sekolah SMK Muda Bawean.'
        ];

        return view('public.pengumuman.index', compact('announcements', 'seo'));
    }

    /**
     * Display the specified announcement.
     */
    public function show(string $slug)
    {
        if (Announcement::onlyTrashed()->where('slug', $slug)->exists()) {
            abort(410);
        }

        $announcement = Announcement::published()->where('slug', $slug)->firstOrFail();
        
        $schema = $this->schemaService->announcement($announcement);

        $seo = [
            'title' => $announcement->title,
            'description' => Str::limit(strip_tags($announcement->content), 155)
        ];

        return view('public.pengumuman.show', compact('announcement', 'schema', 'seo'));
    }

    /**
     * Download attachment for the specified announcement.
     */
    public function download(string $slug)
    {
        if (Announcement::onlyTrashed()->where('slug', $slug)->exists()) {
            abort(410);
        }

        $announcement = Announcement::published()->where('slug', $slug)->firstOrFail();

        if (!$announcement->attachment || !Storage::exists($announcement->attachment)) {
            abort(404);
        }

        return Storage::download($announcement->attachment);
    }
}
