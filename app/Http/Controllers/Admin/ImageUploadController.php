<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ImageUploadController extends Controller
{
    /**
     * Handle image upload from Summernote editor.
     *
     * Saves directly to public/uploads/content/ to avoid storage symlink issues
     * on shared hosting environments. Returns a JSON response with the public URL.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,webp,gif|max:5120',
        ]);

        $file     = $request->file('file');
        $ext      = $file->getClientOriginalExtension();
        $filename = Str::uuid() . '.' . $ext;

        // Simpan langsung ke public/uploads/content/ agar bisa diakses
        // tanpa perlu storage symlink (kompatibel dengan shared hosting)
        $destination = public_path('uploads/content');
        if (!is_dir($destination)) {
            mkdir($destination, 0775, true);
        }

        $file->move($destination, $filename);

        return response()->json([
            'url' => asset('uploads/content/' . $filename),
        ]);
    }
}
