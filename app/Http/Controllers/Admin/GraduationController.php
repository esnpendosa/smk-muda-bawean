<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Graduation;
use App\Services\CsvService;
use Illuminate\Http\Request;

class GraduationController extends Controller
{
    public function __construct(private CsvService $csvService) {}

    public function index(Request $request)
    {
        $query = Graduation::query();
        if ($request->filled('year')) {
            $query->where('academic_year', $request->year);
        }
        $graduations  = $query->orderBy('student_name')->paginate(20);
        $uniqueYears  = Graduation::select('academic_year')->distinct()->orderBy('academic_year', 'desc')->pluck('academic_year');

        return view('admin.graduations.index', compact('graduations', 'uniqueYears'));
    }

    public function create()
    {
        return view('admin.graduations.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('admin.graduations.index');
    }

    public function show($id)
    {
        $graduation = Graduation::findOrFail($id);
        return view('admin.graduations.show', compact('graduation'));
    }

    public function edit($id)
    {
        $graduation = Graduation::findOrFail($id);
        return view('admin.graduations.edit', compact('graduation'));
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('admin.graduations.index');
    }

    public function destroy($id)
    {
        Graduation::findOrFail($id)->delete();
        return redirect()->route('admin.graduations.index')->with('success', 'Data kelulusan dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file'      => 'required|file|mimes:csv,txt|max:5120',
            'academic_year' => 'required|string|max:20',
        ]);

        try {
            $result = $this->csvService->importGraduations(
                $request->file('csv_file'),
                $request->academic_year
            );

            $message = "Berhasil mengimpor {$result['imported']} baris.";
            if ($result['failed'] > 0) {
                $message .= " {$result['failed']} baris gagal.";
            }

            return redirect()->route('admin.graduations.index')
                ->with('success', $message)
                ->with('import_errors', $result['errors']);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        $year = $request->input('academic_year', date('Y'));
        $csv  = $this->csvService->exportGraduations($year);

        return response($csv, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"kelulusan_{$year}.csv\"",
        ]);
    }
}
