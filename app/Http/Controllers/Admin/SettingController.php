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
}
