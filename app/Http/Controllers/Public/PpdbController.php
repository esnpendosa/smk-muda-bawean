<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\PpdbRegistration;
use Illuminate\Http\Request;

class PpdbController extends Controller
{
    /**
     * Display PPDB registration form.
     */
    public function index()
    {
        $seo = [
            'title' => 'Pendaftaran PPDB Online',
            'description' => 'Formulir pendaftaran peserta didik baru (PPDB) SMK Muda Bawean.'
        ];

        return view('public.ppdb.index', compact('seo'));
    }

    /**
     * Store PPDB registration.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:100',
            'birth_place' => 'required|string|max:50',
            'birth_date' => 'required|date|before:today',
            'previous_school' => 'required|string|max:100',
            'parent_name' => 'required|string|max:100',
            'phone' => 'required|string|max:15',
        ]);

        // Generate unique registration number
        $latest = PpdbRegistration::orderBy('id', 'desc')->first();
        $nextId = $latest ? $latest->id + 1 : 1;
        $registrationNumber = 'PPDB-' . date('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        $registration = PpdbRegistration::create(array_merge($validated, [
            'registration_number' => $registrationNumber,
            'status' => 'menunggu'
        ]));

        return redirect()->back()->with([
            'success' => 'Pendaftaran PPDB Anda berhasil disimpan!',
            'registration' => $registration
        ]);
    }
}
