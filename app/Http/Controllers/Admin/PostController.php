<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\SlugService;
use App\Services\HtmlSanitizerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function __construct(
        private SlugService $slugService,
        private HtmlSanitizerService $sanitizer
    ) {}

    public function index(Request $request)
    {
        $query = Post::withTrashed()->with('author')->latest();
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $posts = $query->paginate(15);
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'content'          => 'required|string',
            'status'           => 'required|in:draft,published',
            'thumbnail'        => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'meta_title'       => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'published_at'     => 'nullable|date',
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $ext = $request->file('thumbnail')->getClientOriginalExtension();
            $thumbnailPath = $request->file('thumbnail')->storeAs(
                'uploads', Str::uuid() . '.' . $ext, 'private'
            );
        }

        Post::create([
            'title'            => $validated['title'],
            'slug'             => $this->slugService->generate($validated['title'], Post::class),
            'content'          => $this->sanitizer->clean($validated['content']),
            'thumbnail'        => $thumbnailPath,
            'status'           => $validated['status'],
            'meta_title'       => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'author_id'        => auth()->id(),
            'published_at'     => $validated['published_at'] ?? null,
        ]);

        return redirect()->route('admin.posts.index')->with('success', 'Berita berhasil ditambahkan.');
    }

    public function show($id)
    {
        $post = Post::withTrashed()->findOrFail($id);
        return view('admin.posts.show', compact('post'));
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'content'          => 'required|string',
            'status'           => 'required|in:draft,published',
            'thumbnail'        => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'meta_title'       => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'published_at'     => 'nullable|date',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($post->thumbnail) Storage::disk('private')->delete($post->thumbnail);
            $ext = $request->file('thumbnail')->getClientOriginalExtension();
            $validated['thumbnail'] = $request->file('thumbnail')->storeAs(
                'uploads', Str::uuid() . '.' . $ext, 'private'
            );
        }

        $slug = $post->title !== $validated['title']
            ? $this->slugService->generate($validated['title'], Post::class, $post->id)
            : $post->slug;

        $post->update([
            'title'            => $validated['title'],
            'slug'             => $slug,
            'content'          => $this->sanitizer->clean($validated['content']),
            'thumbnail'        => $validated['thumbnail'] ?? $post->thumbnail,
            'status'           => $validated['status'],
            'meta_title'       => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'published_at'     => $validated['published_at'] ?? null,
        ]);

        return redirect()->route('admin.posts.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Berita berhasil dihapus (soft delete).');
    }
}
