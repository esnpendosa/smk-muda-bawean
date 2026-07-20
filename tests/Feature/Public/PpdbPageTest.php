<?php

namespace Tests\Feature\Public;

use App\Models\PpdbRegistration;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PpdbPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_ppdb_index_returns_successful_response(): void
    {
        $response = $this->get('/ppdb');
        $response->assertStatus(200);
        $response->assertSee('Penerimaan Peserta Didik Baru');
    }

    public function test_ppdb_registration_succeeds_with_valid_data(): void
    {
        $response = $this->post('/ppdb', [
            'full_name' => 'Aditya Nugraha',
            'birth_place' => 'Gresik',
            'birth_date' => '2010-05-15',
            'previous_school' => 'SMP Negeri 1 Sangkapura',
            'parent_name' => 'Bambang Nugraha',
            'phone' => '081234567890',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('ppdb_registrations', [
            'full_name' => 'Aditya Nugraha',
            'previous_school' => 'SMP Negeri 1 Sangkapura',
        ]);

        $registration = PpdbRegistration::first();
        $this->assertNotNull($registration->registration_number);
        // Assert matches pattern PPDB-YYYYMMDD-XXXX
        $this->assertMatchesRegularExpression('/^PPDB-\d{8}-\d{4}$/', $registration->registration_number);
    }

    public function test_ppdb_registration_fails_if_required_fields_missing(): void
    {
        $response = $this->post('/ppdb', [
            'full_name' => '',
            'birth_place' => 'Gresik',
            'birth_date' => '2010-05-15',
        ]);

        $response->assertSessionHasErrors(['full_name', 'previous_school', 'parent_name', 'phone']);
        $this->assertDatabaseEmpty('ppdb_registrations');
    }
}
