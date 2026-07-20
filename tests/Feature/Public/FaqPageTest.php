<?php

namespace Tests\Feature\Public;

use App\Models\Faq;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FaqPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_faq_index_returns_successful_response(): void
    {
        $response = $this->get('/faq');
        $response->assertStatus(200);
        $response->assertSee('Pertanyaan Umum');
    }

    public function test_faq_active_listed_inactive_hidden(): void
    {
        Faq::factory()->create([
            'question' => 'Active Question Test?',
            'answer' => 'Active Answer',
            'is_active' => true,
        ]);

        Faq::factory()->create([
            'question' => 'Inactive Question Test?',
            'answer' => 'Inactive Answer',
            'is_active' => false,
        ]);

        $response = $this->get('/faq');
        $response->assertStatus(200);
        $response->assertSee('Active Question Test?');
        $response->assertDontSee('Inactive Question Test?');
        $response->assertSee('FAQPage');
    }

    public function test_faq_search_filters_results(): void
    {
        Faq::factory()->create([
            'question' => 'How to register to SMK?',
            'answer' => 'You can register online.',
            'is_active' => true,
        ]);

        Faq::factory()->create([
            'question' => 'What is the school fee?',
            'answer' => 'It is free.',
            'is_active' => true,
        ]);

        $response = $this->get('/faq?search=fee');
        $response->assertStatus(200);
        $response->assertSee('What is the school fee?');
        $response->assertDontSee('How to register to SMK?');
    }
}
