<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_edit_user_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $user = User::factory()->create(['role' => 'warehouse', 'is_active' => true]);

        $response = $this->actingAs($admin)
            ->get(route('admin.users.edit', $user->id));

        $response->assertStatus(200);
        $response->assertSee($user->name);
        $response->assertSee('disabled'); // email field should be disabled
    }

    public function test_admin_can_update_user_fields(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $user = User::factory()->create(['role' => 'warehouse', 'is_active' => true]);

        $response = $this->actingAs($admin)
            ->put(route('admin.users.update', $user->id), [
                'name' => 'Updated Name',
                'role' => 'admin',
                'phone' => '08123456789',
                'is_active' => '1',
            ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'role' => 'admin',
            'phone' => '08123456789',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_deactivate_resigned_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $user = User::factory()->create(['role' => 'warehouse', 'is_active' => true]);

        $response = $this->actingAs($admin)
            ->put(route('admin.users.update', $user->id), [
                'name' => $user->name,
                'role' => $user->role,
                'phone' => $user->phone,
                'is_active' => '0', // deactivate
            ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_active' => false,
        ]);
    }

    public function test_inactive_user_cannot_login(): void
    {
        $user = User::factory()->create([
            'email' => 'inactive@example.com',
            'password' => bcrypt('password123'),
            'role' => 'warehouse',
            'is_active' => false,
        ]);

        $response = $this->post(route('login.store'), [
            'email' => 'inactive@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertFalse(auth()->check());
    }

    public function test_inactive_user_gets_logged_out_by_middleware(): void
    {
        $user = User::factory()->create(['role' => 'warehouse', 'is_active' => true]);

        $response = $this->actingAs($user)
            ->get(route('warehouse.dashboard'));

        $response->assertStatus(200);

        // Deactivate user in DB
        $user->update(['is_active' => false]);

        // Request again
        $response2 = $this->get(route('warehouse.dashboard'));

        $response2->assertRedirect(route('login'));
        $this->assertFalse(auth()->check());
    }

    public function test_user_phone_validation_rejects_alphabets(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

        $response = $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'Test User',
                'email' => 'phoneinvalid@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => 'admin',
                'phone' => '0812abc345',
            ]);

        $response->assertSessionHasErrors('phone');
    }
}
