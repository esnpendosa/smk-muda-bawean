<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Graduation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class GraduationController extends Controller
{
    /**
     * Display graduation lookup page.
     */
    public function index()
    {
        $seo = [
            'title' => 'Cek Status Kelulusan',
            'description' => 'Masukkan NISN Anda untuk memeriksa status kelulusan tahun ajaran ini.'
        ];

        return view('public.kelulusan.index', compact('seo'));
    }

    /**
     * Search graduation status.
     */
    public function search(Request $request)
    {
        $key = 'graduation_search:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->view('errors.429', [
                'message' => 'Terlalu banyak percobaan. Harap coba lagi dalam 1 menit.'
            ], 429);
        }
        RateLimiter::hit($key, 60);

        $request->validate([
            'nisn' => 'required|numeric|digits:10',
        ]);

        $graduation = Graduation::where('exam_number', $request->nisn)->first();

        if (!$graduation) {
            return redirect()->back()->with('error', 'NISN tidak ditemukan')->withInput();
        }

        $seo = [
            'title' => 'Hasil Verifikasi Kelulusan - ' . $graduation->student_name,
            'description' => 'Verifikasi status kelulusan siswa SMK Muda Bawean.'
        ];

        return view('public.kelulusan.result', compact('graduation', 'seo'));
    }

    /**
     * Download graduation PDF letter.
     */
    public function download(string $nisn)
    {
        $graduation = Graduation::where('exam_number', $nisn)->firstOrFail();

        if (strtoupper($graduation->status_kelulusan) !== 'LULUS') {
            abort(403, 'Surat kelulusan hanya tersedia untuk siswa yang dinyatakan LULUS.');
        }

        // Minimal valid A4 PDF document containing graduation information
        $pdfContent = "%PDF-1.4\n" .
            "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n" .
            "2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n" .
            "3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> >> >> /Contents 4 0 R >>\nendobj\n" .
            "4 0 obj\n<< /Length 200 >>\nstream\n" .
            "BT\n/F1 14 Tf\n50 750 Td\n(SURAT KETERANGAN KELULUSAN) Tj\n" .
            "0 -30 Td\n(SMK MUDA BAWEAN) Tj\n" .
            "0 -40 Td\n(Nama: {$graduation->student_name}) Tj\n" .
            "0 -20 Td\n(NISN: {$graduation->exam_number}) Tj\n" .
            "0 -20 Td\n(Program Keahlian: {$graduation->program_keahlian}) Tj\n" .
            "0 -20 Td\n(Status: {$graduation->status_kelulusan}) Tj\n" .
            "ET\nstreamend\nendobj\nxref\n0 5\n0000000000 65535 f\n0000000009 00000 n\n0000000056 00000 n\n0000000111 00000 n\n0000000282 00000 n\ntrailer\n<< /Size 5 /Root 1 0 R >>\nstartxref\n500\n%%EOF";

        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="sklk_' . $nisn . '.pdf"',
        ]);
    }
}
