<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PpdbRegistration;
use Illuminate\Http\Request;

class PpdbController extends Controller
{
    public function index(Request $request)
    {
        $query = PpdbRegistration::latest();
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $registrations = $query->paginate(20);
        return view('admin.ppdb.index', compact('registrations'));
    }

    public function show($id)
    {
        $registration = PpdbRegistration::findOrFail($id);
        return view('admin.ppdb.show', compact('registration'));
    }

    public function update(Request $request, $id)
    {
        $registration = PpdbRegistration::findOrFail($id);

        $request->validate([
            'status' => 'required|in:menunggu,diterima,ditolak',
        ]);

        $registration->update(['status' => $request->status]);

        return redirect()->route('admin.ppdb.show', $id)->with('success', 'Status pendaftar berhasil diperbarui.');
    }

    public function export(Request $request)
    {
        $query = PpdbRegistration::query();
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $registrations = $query->orderBy('created_at')->get();

        $stream = fopen('php://temp', 'r+');
        fputcsv($stream, [
            'registration_number', 'full_name', 'birth_place', 'birth_date',
            'previous_school', 'parent_name', 'phone', 'status', 'created_at'
        ]);
        foreach ($registrations as $r) {
            fputcsv($stream, [
                $r->registration_number,
                $r->full_name,
                $r->birth_place,
                $r->birth_date?->format('Y-m-d'),
                $r->previous_school,
                $r->parent_name,
                $r->phone,
                $r->status,
                $r->created_at->format('Y-m-d H:i:s'),
            ]);
        }
        rewind($stream);
        $csv = stream_get_contents($stream);
        fclose($stream);

        return response($csv, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="ppdb_export.csv"',
        ]);
    }
}
