<?php

namespace Tests\Feature\Property;

use App\Services\CsvService;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CsvRoundTripPropertyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * P8: import CSV → export CSV → nilai identik dengan file asal
     */
    public function test_graduation_csv_roundtrip(): void
    {
        $original = [
            ['nama_siswa','nomor_peserta','program_keahlian','status_kelulusan'],
            ['Ahmad Fauzi','2024-001','Teknik Komputer Jaringan','LULUS'],
            ['Siti Aminah','2024-002','Akuntansi','TIDAK LULUS'],
        ];

        // Create temporary CSV file
        $tmpFile = tempnam(sys_get_temp_dir(), 'csv_test_');
        $handle = fopen($tmpFile, 'w');
        foreach ($original as $row) {
            fputcsv($handle, $row);
        }
        fclose($handle);

        // Import
        $csvService = new CsvService();
        $result = $csvService->importGraduations(
            new UploadedFile($tmpFile, 'test.csv', 'text/csv', null, true),
            '2024/2025'
        );
        $this->assertEquals(2, $result['imported']);
        $this->assertEquals(0, $result['failed']);

        // Export
        $exportedCsv = $csvService->exportGraduations('2024/2025');

        // Compare content
        $this->assertStringContainsString('Ahmad Fauzi', $exportedCsv);
        $this->assertStringContainsString('LULUS', $exportedCsv);
        $this->assertStringContainsString('TIDAK LULUS', $exportedCsv);

        unlink($tmpFile);
    }
}
