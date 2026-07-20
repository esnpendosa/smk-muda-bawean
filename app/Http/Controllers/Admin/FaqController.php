<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::ordered()->paginate(20);
        return view('admin.faqs.index', compact('faqs'));
    }

    public function create()
    {
        return view('admin.faqs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
            'answer'   => 'required|string',
            'order'    => 'nullable|integer|min:0',
            'is_active'=> 'sometimes|boolean',
        ]);

        Faq::create([
            'question'  => $request->question,
            'answer'    => $request->answer,
            'order'     => $request->order ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ berhasil ditambahkan.');
    }

    public function show($id)
    {
        return redirect()->route('admin.faqs.edit', $id);
    }

    public function edit($id)
    {
        $faq = Faq::findOrFail($id);
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(Request $request, $id)
    {
        $faq = Faq::findOrFail($id);

        $request->validate([
            'question' => 'required|string',
            'answer'   => 'required|string',
            'order'    => 'nullable|integer|min:0',
            'is_active'=> 'sometimes|boolean',
        ]);

        $faq->update([
            'question'  => $request->question,
            'answer'    => $request->answer,
            'order'     => $request->order ?? $faq->order,
            'is_active' => $request->boolean('is_active', false),
        ]);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Faq::findOrFail($id)->delete();
        return redirect()->route('admin.faqs.index')->with('success', 'FAQ berhasil dihapus.');
    }
}
