<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Services\ImageProcessingService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TeacherController extends Controller
{
    public function __construct(
        private ImageProcessingService $imageProcessor
    ) {}
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
            'photo'    => 'nullable|file|mimes:jpg,jpeg,png,webp|max:10240',
            'order'    => 'nullable|integer|min:0',
        ]);

        // Auto-compress & resize foto pendidik ke max 600px lebar
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $this->imageProcessor->process(
                $request->file('photo'), '', 600, 85
            );
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
            'photo'    => 'nullable|file|mimes:jpg,jpeg,png,webp|max:10240',
            'order'    => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('photo')) {
            if ($teacher->photo && str_starts_with($teacher->photo, 'uploads/')) {
                $oldPath = public_path($teacher->photo);
                if (file_exists($oldPath)) @unlink($oldPath);
            }
            // Auto-compress & resize foto baru
            $validated['photo'] = $this->imageProcessor->process(
                $request->file('photo'), '', 600, 85
            );
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
        // Hapus foto jika tersimpan di public/uploads/
        if ($teacher->photo && str_starts_with($teacher->photo, 'uploads/')) {
            $oldPath = public_path($teacher->photo);
            if (file_exists($oldPath)) @unlink($oldPath);
        }
        $teacher->delete();

        return redirect()->route('admin.teachers.index')->with('success', 'Data pendidik berhasil dihapus.');
    }
}
