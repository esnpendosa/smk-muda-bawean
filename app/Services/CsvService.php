<?php

namespace App\Services;

use App\Models\Graduation;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;

class CsvService
{
    /**
     * Import graduations from a CSV file.
     */
    public function importGraduations(UploadedFile $file, string $academicYear): array
    {
        $requiredColumns = ['nama_siswa', 'nomor_peserta', 'program_keahlian', 'status_kelulusan'];

        $path = $file->getRealPath();
        $handle = fopen($path, 'r');

        if (!$handle) {
            throw new \Exception('Gagal membuka file CSV.');
        }

        // Read and trim headers
        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);
            throw new \Exception('File CSV kosong.');
        }

        $header = array_map('trim', array_map('strtolower', $header));

        // Check if any required headers are missing
        $missing = array_diff($requiredColumns, $header);
        if (!empty($missing)) {
            fclose($handle);
            throw new \Exception('Kolom tidak ditemukan: ' . implode(', ', $missing));
        }

        $imported = 0;
        $failed = 0;
        $errors = [];
        $row = 1;

        // Map header indices to keys
        $headerMap = array_flip($header);

        while (($data = fgetcsv($handle)) !== false) {
            $row++;
            
            // Handle row elements mismatch
            if (count($data) !== count($header)) {
                $failed++;
                $errors[] = "Baris {$row}: Jumlah kolom tidak cocok dengan header.";
                continue;
            }

            $record = array_combine($header, $data);
            $record = array_map('trim', $record);

            // Validation rules
            $validator = Validator::make($record, [
                'nama_siswa' => 'required|string|max:100',
                'nomor_peserta' => 'required|string|max:50',
                'program_keahlian' => 'required|string|max:100',
                'status_kelulusan' => 'required|string|in:LULUS,TIDAK LULUS,lulus,tidak lulus',
            ]);

            if ($validator->fails()) {
                $failed++;
                $errors[] = "Baris {$row}: " . implode(' ', $validator->errors()->all());
                continue;
            }

            try {
                Graduation::updateOrCreate(
                    ['exam_number' => $record['nomor_peserta']],
                    [
                        'academic_year' => $academicYear,
                        'student_name' => $record['nama_siswa'],
                        'program_keahlian' => $record['program_keahlian'],
                        'status_kelulusan' => strtoupper($record['status_kelulusan']),
                    ]
                );
                $imported++;
            } catch (\Exception $e) {
                $failed++;
                $errors[] = "Baris {$row}: " . $e->getMessage();
            }
        }

        fclose($handle);
        return compact('imported', 'failed', 'errors');
    }

    /**
     * Export graduations of a specific academic year to a CSV string.
     */
    public function exportGraduations(string $academicYear): string
    {
        $graduations = Graduation::where('academic_year', $academicYear)->get();

        $stream = fopen('php://temp', 'r+');

        // Write header
        fputcsv($stream, ['Nama Siswa', 'Nomor Peserta', 'Program Keahlian', 'Status Kelulusan']);

        foreach ($graduations as $g) {
            fputcsv($stream, [
                $g->student_name,
                $g->exam_number,
                $g->program_keahlian,
                $g->status_kelulusan
            ]);
        }

        rewind($stream);
        $csv = stream_get_contents($stream);
        fclose($stream);

        return $csv;
    }
}
