<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Teacher;
use App\Models\Setting;
use App\Services\CacheService;
use App\Services\SchemaMarkupService;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    protected CacheService $cacheService;
    protected SchemaMarkupService $schemaService;

    public function __construct(CacheService $cacheService, SchemaMarkupService $schemaService)
    {
        $this->cacheService = $cacheService;
        $this->schemaService = $schemaService;
    }

    /**
     * Display Sejarah page.
     */
    public function sejarah()
    {
        $page = $this->cacheService->remember('profil_sejarah', 3600, function () {
            return Page::where('slug', 'sejarah')->first() ?? Page::create([
                'slug' => 'sejarah',
                'title' => 'Sejarah Sekolah',
                'content' => 'Sejarah SMK Muda Bawean...'
            ]);
        });

        $settings = [
            'school_name' => Setting::get('school_name', 'SMK Muda Bawean'),
            'school_address' => Setting::get('school_address', 'Jl. Daendels No. 1, Sangkapura, Bawean'),
            'school_phone' => Setting::get('school_phone'),
            'school_email' => Setting::get('school_email'),
        ];
        $schema = $this->schemaService->educationalOrganization($settings);

        $seo = [
            'title' => $page->title,
            'description' => $page->meta_description ?? Str::limit(strip_tags($page->content), 155)
        ];

        return view('public.profil.sejarah', compact('page', 'schema', 'seo'));
    }

    /**
     * Display Visi & Misi page.
     */
    public function visiMisi()
    {
        $page = $this->cacheService->remember('profil_visi_misi', 3600, function () {
            return Page::where('slug', 'visi-misi')->first() ?? Page::create([
                'slug' => 'visi-misi',
                'title' => 'Visi & Misi',
                'content' => 'Visi dan Misi SMK Muda Bawean...'
            ]);
        });

        $settings = [
            'school_name' => Setting::get('school_name', 'SMK Muda Bawean'),
            'school_address' => Setting::get('school_address', 'Jl. Daendels No. 1, Sangkapura, Bawean'),
            'school_phone' => Setting::get('school_phone'),
            'school_email' => Setting::get('school_email'),
        ];
        $schema = $this->schemaService->educationalOrganization($settings);

        $seo = [
            'title' => $page->title,
            'description' => $page->meta_description ?? Str::limit(strip_tags($page->content), 155)
        ];

        return view('public.profil.visi-misi', compact('page', 'schema', 'seo'));
    }

    /**
     * Display teachers page.
     */
    public function pendidik()
    {
        $teachers = $this->cacheService->remember('profil_pendidik', 3600, function () {
            return Teacher::ordered()->get();
        });

        $seo = [
            'title' => 'Daftar Pendidik',
            'description' => 'Profil Guru dan Staff SMK Muda Bawean'
        ];

        return view('public.profil.pendidik', compact('teachers', 'seo'));
    }
}
