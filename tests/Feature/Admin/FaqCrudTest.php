<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Faq;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FaqCrudTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_faq_index_accessible(): void
    {
        Faq::factory()->count(3)->create();
        $response = $this->actingAs($this->admin())->get('/admin/faqs');
        $response->assertStatus(200);
        $response->assertSee('FAQ');
    }

    public function test_faq_store_creates_record(): void
    {
        $admin = $this->admin();
        $response = $this->actingAs($admin)->post('/admin/faqs', [
            'question'  => 'Apa itu SMK Muda Bawean?',
            'answer'    => 'Sekolah menengah kejuruan di Pulau Bawean.',
            'is_active' => '1',
            'order'     => 0,
        ]);

        $response->assertRedirect(route('admin.faqs.index'));
        $this->assertDatabaseHas('faqs', ['question' => 'Apa itu SMK Muda Bawean?', 'is_active' => true]);
    }

    public function test_faq_store_requires_question_and_answer(): void
    {
        $admin = $this->admin();
        $response = $this->actingAs($admin)->post('/admin/faqs', [
            'question' => '',
            'answer'   => '',
        ]);
        $response->assertSessionHasErrors(['question', 'answer']);
    }

    public function test_faq_update_changes_active_state(): void
    {
        $admin = $this->admin();
        $faq   = Faq::factory()->create(['is_active' => true]);

        $this->actingAs($admin)->put("/admin/faqs/{$faq->id}", [
            'question' => $faq->question,
            'answer'   => $faq->answer,
            // is_active not sent → false
        ]);

        $this->assertDatabaseHas('faqs', ['id' => $faq->id, 'is_active' => false]);
    }

    public function test_faq_destroy_deletes_record(): void
    {
        $admin = $this->admin();
        $faq   = Faq::factory()->create();

        $this->actingAs($admin)->delete("/admin/faqs/{$faq->id}");

        $this->assertDatabaseMissing('faqs', ['id' => $faq->id]);
    }

    public function test_unauthenticated_cannot_access_faqs(): void
    {
        $response = $this->get('/admin/faqs');
        $response->assertRedirect(route('admin.login'));
    }
}
