<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Services\SlugService;
use App\Services\HtmlSanitizerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AnnouncementController extends Controller
{
    public function __construct(
        private SlugService $slugService,
        private HtmlSanitizerService $sanitizer
    ) {}

    public function index(Request $request)
    {
        $query = Announcement::withTrashed()->latest();
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $announcements = $query->paginate(15);
        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'content'          => 'nullable|string',
            'status'           => 'required|in:draft,published',
            'attachment'       => 'nullable|file|mimes:pdf|max:2048',
            'meta_title'       => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'published_at'     => 'nullable|date',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->storeAs(
                'uploads', Str::uuid() . '.pdf', 'private'
            );
        }

        Announcement::create([
            'title'            => $validated['title'],
            'slug'             => $this->slugService->generate($validated['title'], Announcement::class),
            'content'          => isset($validated['content']) ? $this->sanitizer->clean($validated['content']) : null,
            'attachment'       => $attachmentPath,
            'status'           => $validated['status'],
            'meta_title'       => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'published_at'     => $validated['published_at'] ?? null,
        ]);

        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    public function show($id)
    {
        $announcement = Announcement::withTrashed()->findOrFail($id);
        return view('admin.announcements.show', compact('announcement'));
    }

    public function edit($id)
    {
        $announcement = Announcement::findOrFail($id);
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);

        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'content'          => 'nullable|string',
            'status'           => 'required|in:draft,published',
            'attachment'       => 'nullable|file|mimes:pdf|max:2048',
            'meta_title'       => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'published_at'     => 'nullable|date',
        ]);

        if ($request->hasFile('attachment')) {
            if ($announcement->attachment) Storage::disk('private')->delete($announcement->attachment);
            $validated['attachment'] = $request->file('attachment')->storeAs(
                'uploads', Str::uuid() . '.pdf', 'private'
            );
        }

        $slug = $announcement->title !== $validated['title']
            ? $this->slugService->generate($validated['title'], Announcement::class, $announcement->id)
            : $announcement->slug;

        $announcement->update([
            'title'            => $validated['title'],
            'slug'             => $slug,
            'content'          => isset($validated['content']) ? $this->sanitizer->clean($validated['content']) : null,
            'attachment'       => $validated['attachment'] ?? $announcement->attachment,
            'status'           => $validated['status'],
            'meta_title'       => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'published_at'     => $validated['published_at'] ?? null,
        ]);

        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->delete();

        return redirect()->route('admin.announcements.index')->with('success', 'Pengumuman berhasil dihapus.');
    }
}
