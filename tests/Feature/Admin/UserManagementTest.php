<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function editor(): User
    {
        return User::factory()->create(['role' => 'editor']);
    }

    public function test_user_index_accessible_by_admin(): void
    {
        $response = $this->actingAs($this->admin())->get('/admin/users');
        $response->assertStatus(200);
        $response->assertSee('Pengguna');
    }

    public function test_editor_cannot_access_user_list(): void
    {
        $response = $this->actingAs($this->editor())->get('/admin/users');
        $response->assertStatus(403);
    }

    public function test_create_user_with_valid_data(): void
    {
        $admin = $this->admin();
        $response = $this->actingAs($admin)->post('/admin/users', [
            'name'                  => 'Budi Editor',
            'email'                 => 'budi@test.com',
            'password'              => 'Secret123!',
            'password_confirmation' => 'Secret123!',
            'role'                  => 'editor',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['email' => 'budi@test.com', 'role' => 'editor']);
    }

    public function test_duplicate_email_rejected(): void
    {
        $admin = $this->admin();
        User::factory()->create(['email' => 'taken@test.com']);

        $response = $this->actingAs($admin)->post('/admin/users', [
            'name'                  => 'Duplicate',
            'email'                 => 'taken@test.com',
            'password'              => 'Secret123!',
            'password_confirmation' => 'Secret123!',
            'role'                  => 'editor',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_update_user_without_changing_password(): void
    {
        $admin    = $this->admin();
        $oldHash  = bcrypt('Original123!');
        $target   = User::factory()->create(['password' => $oldHash, 'role' => 'editor']);

        $this->actingAs($admin)->put("/admin/users/{$target->id}", [
            'name'  => 'New Name',
            'email' => $target->email,
            'role'  => 'editor',
        ]);

        $target->refresh();
        $this->assertEquals('New Name', $target->name);
        $this->assertTrue(Hash::check('Original123!', $target->password));
    }

    public function test_admin_cannot_delete_themselves(): void
    {
        $admin    = $this->admin();
        $response = $this->actingAs($admin)->delete("/admin/users/{$admin->id}");
        $response->assertStatus(403);
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_admin_can_delete_another_user(): void
    {
        $admin  = $this->admin();
        $target = User::factory()->create(['role' => 'editor']);
        $this->actingAs($admin)->delete("/admin/users/{$target->id}");
        $this->assertDatabaseMissing('users', ['id' => $target->id]);
    }
}
