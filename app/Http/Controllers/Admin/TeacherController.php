<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::ordered()->paginate(20);
        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('admin.teachers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'position' => 'required|string|max:100',
            'photo'    => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'order'    => 'nullable|integer|min:0',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $ext = $request->file('photo')->getClientOriginalExtension();
            $photoPath = $request->file('photo')->storeAs('uploads', Str::uuid() . '.' . $ext, 'private');
        }

        Teacher::create([
            'name'     => $validated['name'],
            'position' => $validated['position'],
            'photo'    => $photoPath,
            'order'    => $validated['order'] ?? 0,
        ]);

        return redirect()->route('admin.teachers.index')->with('success', 'Data pendidik berhasil ditambahkan.');
    }

    public function show($id)
    {
        return redirect()->route('admin.teachers.edit', $id);
    }

    public function edit($id)
    {
        $teacher = Teacher::findOrFail($id);
        return view('admin.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);

        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'position' => 'required|string|max:100',
            'photo'    => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'order'    => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('photo')) {
            if ($teacher->photo) Storage::disk('private')->delete($teacher->photo);
            $ext = $request->file('photo')->getClientOriginalExtension();
            $validated['photo'] = $request->file('photo')->storeAs('uploads', Str::uuid() . '.' . $ext, 'private');
        }

        $teacher->update([
            'name'     => $validated['name'],
            'position' => $validated['position'],
            'photo'    => $validated['photo'] ?? $teacher->photo,
            'order'    => $validated['order'] ?? $teacher->order,
        ]);

        return redirect()->route('admin.teachers.index')->with('success', 'Data pendidik berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $teacher = Teacher::findOrFail($id);
        if ($teacher->photo) Storage::disk('private')->delete($teacher->photo);
        $teacher->delete();

        return redirect()->route('admin.teachers.index')->with('success', 'Data pendidik berhasil dihapus.');
    }
}
