<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Services\HtmlSanitizerService;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function __construct(private HtmlSanitizerService $sanitizer) {}

    public function index()
    {
        $pages = Page::all();
        return view('admin.pages.index', compact('pages'));
    }

    public function show($id)
    {
        return redirect()->route('admin.pages.edit', $id);
    }

    public function edit($id)
    {
        $page = Page::findOrFail($id);
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);

        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'content'          => 'required|string',
            'meta_title'       => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
        ]);

        $page->update([
            'title'            => $validated['title'],
            'content'          => $this->sanitizer->clean($validated['content']),
            'meta_title'       => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
        ]);

        return redirect()->route('admin.pages.index')->with('success', 'Halaman statis berhasil diperbarui.');
    }
}
