<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\TracerStudy;
use Illuminate\Http\Request;

class AlumniController extends Controller
{
    /**
     * Display a listing of alumni.
     */
    public function index(Request $request)
    {
        $query = Alumni::query()->with(['tracerStudies']);

        if ($request->filled('search')) {
            $query->where('full_name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('year')) {
            $query->where('graduation_year', $request->year);
        }

        $alumni = $query->orderBy('full_name', 'asc')->paginate(15);
        $uniqueYears = Alumni::select('graduation_year')->distinct()->orderBy('graduation_year', 'desc')->pluck('graduation_year');

        $seo = [
            'title' => 'Direktori Alumni',
            'description' => 'Cari dan terhubung dengan alumni SMK Muda Bawean.'
        ];

        return view('public.alumni.index', compact('alumni', 'uniqueYears', 'seo'));
    }

    /**
     * Store a newly created alumni in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:100',
            'graduation_year' => 'required|integer|min:1990|max:' . (date('Y') + 2),
            'email' => 'required|email|unique:alumni,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        Alumni::create($validated);

        return redirect()->back()->with('success', 'Data alumni berhasil didaftarkan.');
    }

    /**
     * Display tracer study form.
     */
    public function tracerStudy()
    {
        $seo = [
            'title' => 'Formulir Tracer Study',
            'description' => 'Formulir penelusuran lulusan (tracer study) SMK Muda Bawean.'
        ];

        return view('public.alumni.tracer', compact('seo'));
    }

    /**
     * Store tracer study.
     */
    public function storeTracerStudy(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'education_status' => 'required|in:tidak_kuliah,kuliah',
            'employment_status' => 'required|in:tidak_bekerja,bekerja',
        ]);

        $alumni = Alumni::where('email', $request->email)->first();

        if (!$alumni) {
            return redirect()->back()->with('error', 'Email tidak terdaftar sebagai alumni. Harap daftarkan data alumni Anda terlebih dahulu.')->withInput();
        }

        TracerStudy::create([
            'alumni_id' => $alumni->id,
            'full_name' => $alumni->full_name,
            'graduation_year' => $alumni->graduation_year,
            'education_status' => $request->education_status,
            'employment_status' => $request->employment_status,
        ]);

        return redirect()->back()->with('success', 'Data Tracer Study berhasil disimpan.');
    }
}
