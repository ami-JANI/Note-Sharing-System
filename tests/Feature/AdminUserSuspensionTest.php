<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserSuspensionTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_access_user_list(): void
    {
        $student = User::factory()->create(['role' => 'student']);

        $this->actingAs($student)->get(route('admin.users'))->assertForbidden();
    }

    public function test_admin_can_suspend_a_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create(['role' => 'student']);

        $this->actingAs($admin)->post(route('admin.users.suspend', $student))->assertRedirect();

        $this->assertEquals('suspended', $student->fresh()->status);
    }

    public function test_admin_can_unsuspend_a_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create(['role' => 'student', 'status' => 'suspended']);

        $this->actingAs($admin)->post(route('admin.users.unsuspend', $student))->assertRedirect();

        $this->assertEquals('active', $student->fresh()->status);
    }

    public function test_admin_accounts_cannot_be_suspended(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $otherAdmin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->post(route('admin.users.suspend', $otherAdmin))->assertForbidden();
    }

    public function test_suspended_user_cannot_log_in(): void
    {
        $user = User::factory()->create(['status' => 'suspended']);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}
