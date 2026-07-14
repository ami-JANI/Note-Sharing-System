<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\AdminBroadcastNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AdminBroadcastNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_broadcast(): void
    {
        $user = User::factory()->create(['role' => 'student']);

        $response = $this->actingAs($user)->post(route('admin.notifications.broadcast'), [
            'message' => 'Test',
            'target' => 'all',
        ]);

        $response->assertForbidden();
    }

    public function test_admin_can_broadcast_to_all(): void
    {
        Notification::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        User::factory()->count(3)->create();

        $this->actingAs($admin)->post(route('admin.notifications.broadcast'), [
            'message' => 'Hello everyone!',
            'target' => 'all',
        ]);

        Notification::assertSentTo(User::all(), AdminBroadcastNotification::class);
    }

    public function test_admin_can_broadcast_to_specific_users(): void
    {
        Notification::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        User::factory()->create();

        $this->actingAs($admin)->post(route('admin.notifications.broadcast'), [
            'message' => 'Hello specific!',
            'target' => 'specific',
            'user_ids' => [$user1->id, $user2->id],
        ]);

        Notification::assertSentTo($user1, AdminBroadcastNotification::class);
        Notification::assertSentTo($user2, AdminBroadcastNotification::class);
    }

    public function test_broadcast_lands_in_database(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();

        $this->actingAs($admin)->post(route('admin.notifications.broadcast'), [
            'message' => 'DB test',
            'target' => 'all',
        ]);

        $this->assertDatabaseHas('notifications', [
            'type' => AdminBroadcastNotification::class,
            'notifiable_id' => $user->id,
        ]);
    }
}
