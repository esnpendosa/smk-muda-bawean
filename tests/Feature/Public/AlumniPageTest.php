<?php

namespace Tests\Feature\Public;

use App\Models\Alumni;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AlumniPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_alumni_index_returns_successful_response(): void
    {
        $response = $this->get('/alumni');
        $response->assertStatus(200);
        $response->assertSee('Direktori Alumni');
    }

    public function test_alumni_search_filters_results(): void
    {
        Alumni::factory()->create([
            'full_name' => 'Fulan bin Fulan',
            'graduation_year' => 2022,
            'email' => 'fulan@example.com',
        ]);

        Alumni::factory()->create([
            'full_name' => 'John Doe',
            'graduation_year' => 2023,
            'email' => 'john@example.com',
        ]);

        $response = $this->get('/alumni?search=Fulan');
        $response->assertStatus(200);
        $response->assertSee('Fulan bin Fulan');
        $response->assertDontSee('John Doe');

        $responseYear = $this->get('/alumni?year=2023');
        $responseYear->assertStatus(200);
        $responseYear->assertSee('John Doe');
        $responseYear->assertDontSee('Fulan bin Fulan');
    }

    public function test_alumni_registration_succeeds_with_valid_data(): void
    {
        $response = $this->post('/alumni', [
            'full_name' => 'Jane Smith',
            'graduation_year' => 2021,
            'email' => 'jane@example.com',
            'phone' => '08123456789',
            'address' => 'Gresik, Jawa Timur',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('alumni', [
            'email' => 'jane@example.com',
        ]);
    }

    public function test_alumni_registration_fails_if_email_exists(): void
    {
        Alumni::factory()->create([
            'email' => 'jane@example.com',
        ]);

        $response = $this->post('/alumni', [
            'full_name' => 'Jane Smith',
            'graduation_year' => 2021,
            'email' => 'jane@example.com',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_tracer_study_form_returns_successful_response(): void
    {
        $response = $this->get('/alumni/tracer-study');
        $response->assertStatus(200);
        $response->assertSee('Formulir Tracer Study');
    }

    public function test_store_tracer_study_succeeds_for_registered_email(): void
    {
        $alumni = Alumni::factory()->create([
            'email' => 'alumni@example.com',
        ]);

        $response = $this->post('/alumni/tracer-study', [
            'email' => 'alumni@example.com',
            'education_status' => 'kuliah',
            'employment_status' => 'bekerja',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('tracer_studies', [
            'alumni_id' => $alumni->id,
            'education_status' => 'kuliah',
            'employment_status' => 'bekerja',
        ]);
    }

    public function test_store_tracer_study_fails_for_unregistered_email(): void
    {
        $response = $this->post('/alumni/tracer-study', [
            'email' => 'unknown@example.com',
            'education_status' => 'kuliah',
            'employment_status' => 'bekerja',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('error');
        $this->assertDatabaseEmpty('tracer_studies');
    }
}
