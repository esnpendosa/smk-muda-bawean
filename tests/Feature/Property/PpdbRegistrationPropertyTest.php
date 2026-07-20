<?php

namespace Tests\Feature\Property;

use App\Models\PpdbRegistration;
use App\Services\PpdbService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PpdbRegistrationPropertyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * P5: N pendaftaran berhasil → jumlah nomor registrasi unik = N
     */
    public function test_ppdb_registration_numbers_are_unique(): void
    {
        $service = new PpdbService();
        $numbers = [];
        $n = 20;

        for ($i = 0; $i < $n; $i++) {
            $num = $service->generateRegistrationNumber();
            $numbers[] = $num;
            
            // Insert to DB to simulate real-world uniqueness validation
            PpdbRegistration::create([
                'registration_number' => $num,
                'full_name' => 'Student ' . $i,
                'birth_place' => 'Bawean',
                'birth_date' => '2010-01-01',
                'previous_school' => 'SMP 1 Bawean',
                'parent_name' => 'Parent ' . $i,
                'phone' => '081234567890',
            ]);
        }

        $unique = array_unique($numbers);
        $this->assertCount($n, $unique, 'Ditemukan nomor registrasi duplikat');
    }
}
