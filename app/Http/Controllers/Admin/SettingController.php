<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $fields = [
            'school_name', 'school_address', 'school_phone', 'school_email',
            'school_geo_lat', 'school_geo_lng',
        ];
        foreach ($fields as $field) {
            Setting::set($field, $request->input($field, ''));
        }
        return redirect()->route('admin.settings.index')->with('success', 'Pengaturan berhasil disimpan.');
    }

    public function seo()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        return view('admin.settings.seo', compact('settings'));
    }

    public function updateSeo(Request $request)
    {
        Setting::set('robots_txt', $request->input('robots_txt', ''));
        Setting::set('meta_description', $request->input('meta_description', ''));
        return redirect()->route('admin.settings.seo')->with('success', 'Pengaturan SEO berhasil disimpan.');
    }

    public function theme()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        return view('admin.settings.theme', compact('settings'));
    }

    public function updateTheme(Request $request)
    {
        $request->validate([
            'color_primary'   => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'color_secondary' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
            'color_accent'    => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        Setting::set('color_primary',   $request->color_primary);
        Setting::set('color_secondary', $request->color_secondary);
        Setting::set('color_accent',    $request->color_accent);

        return redirect()->route('admin.settings.theme')->with('success', 'Tema berhasil disimpan.');
    }

    public function slider()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        return view('admin.settings.slider', compact('settings'));
    }

    public function updateSlider(Request $request)
    {
        $request->validate([
            'slider_slide1_bg' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'slider_slide2_bg' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'slider_slide3_bg' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        $fields = [
            'slider_slide1_title', 'slider_slide1_highlight', 'slider_slide1_desc', 'slider_slide1_btn1_text', 'slider_slide1_btn1_link', 'slider_slide1_btn2_text', 'slider_slide1_btn2_link',
            'slider_slide2_title', 'slider_slide2_highlight', 'slider_slide2_desc', 'slider_slide2_btn1_text', 'slider_slide2_btn1_link', 'slider_slide2_btn2_text', 'slider_slide2_btn2_link',
            'slider_slide3_title', 'slider_slide3_highlight', 'slider_slide3_desc', 'slider_slide3_btn1_text', 'slider_slide3_btn1_link', 'slider_slide3_btn2_text', 'slider_slide3_btn2_link',
        ];

        foreach ($fields as $field) {
            Setting::set($field, $request->input($field, ''));
        }

        // Handle file uploads for background images
        for ($i = 1; $i <= 3; $i++) {
            $key = "slider_slide{$i}_bg";
            if ($request->hasFile($key)) {
                // Hapus gambar lama jika tersimpan di public/uploads/
                $oldPath = Setting::get($key);
                if ($oldPath && str_starts_with($oldPath, 'uploads/')) {
                    $oldFile = public_path($oldPath);
                    if (file_exists($oldFile)) @unlink($oldFile);
                }
                // Simpan langsung ke public/uploads/ (tidak butuh symlink)
                $file     = $request->file($key);
                $ext      = $file->getClientOriginalExtension();
                $filename = \Illuminate\Support\Str::uuid() . '.' . $ext;
                $dest     = public_path('uploads');
                if (!is_dir($dest)) mkdir($dest, 0775, true);
                $file->move($dest, $filename);
                Setting::set($key, 'uploads/' . $filename);
            }
        }

        return redirect()->route('admin.settings.slider')->with('success', 'Hero Slider berhasil disimpan.');
    }
}
