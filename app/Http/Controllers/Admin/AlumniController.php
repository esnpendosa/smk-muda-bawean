<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\TracerStudy;
use Illuminate\Http\Request;

class AlumniController extends Controller
{
    public function index(Request $request)
    {
        $query = Alumni::with('tracerStudies')->orderBy('full_name');
        if ($request->filled('search')) {
            $query->where('full_name', 'like', '%' . $request->search . '%');
        }
        $alumni = $query->paginate(20);
        return view('admin.alumni.index', compact('alumni'));
    }

    public function create()
    {
        return redirect()->route('admin.alumni.index');
    }

    public function store(Request $request)
    {
        return redirect()->route('admin.alumni.index');
    }

    public function show($id)
    {
        // Only admin can see individual alumni detail (enforced by route middleware in admin.php)
        $alumni = Alumni::with('tracerStudies')->findOrFail($id);
        return view('admin.alumni.show', compact('alumni'));
    }

    public function edit($id)
    {
        return redirect()->route('admin.alumni.index');
    }

    public function update($id)
    {
        return redirect()->route('admin.alumni.index');
    }

    public function destroy($id)
    {
        Alumni::findOrFail($id)->delete();
        return redirect()->route('admin.alumni.index')->with('success', 'Data alumni dihapus.');
    }

    public function tracerStudies()
    {
        $total     = TracerStudy::count();
        $kuliah    = TracerStudy::where('education_status', 'kuliah')->count();
        $bekerja   = TracerStudy::where('employment_status', 'bekerja')->count();
        $pctKuliah = $total > 0 ? round($kuliah / $total * 100, 1) : 0;
        $pctBekerja= $total > 0 ? round($bekerja / $total * 100, 1) : 0;

        return view('admin.alumni.tracer-studies', compact('total', 'kuliah', 'bekerja', 'pctKuliah', 'pctBekerja'));
    }
}
