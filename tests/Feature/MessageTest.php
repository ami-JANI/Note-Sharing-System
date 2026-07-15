<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Models\User;
use App\Notifications\NewMessageNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_send_message(): void
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        $this->actingAs($sender)->post(route('messages.store'), [
            'recipient_id' => $recipient->id,
            'body' => 'Hello there!',
        ]);

        $this->assertDatabaseHas('messages', [
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'body' => 'Hello there!',
        ]);
    }

    public function test_cannot_message_yourself(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('messages.store'), [
            'recipient_id' => $user->id,
            'body' => 'Hey me!',
        ]);

        $response->assertSessionHasErrors('recipient_id');
    }

    public function test_new_message_notification_is_fired(): void
    {
        Notification::fake();

        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        $this->actingAs($sender)->post(route('messages.store'), [
            'recipient_id' => $recipient->id,
            'body' => 'Hello!',
        ]);

        Notification::assertSentTo(
            $recipient,
            NewMessageNotification::class,
            fn($n) => $n->sender->id === $sender->id
        );
    }

    public function test_new_message_notification_lands_in_database(): void
    {
        $sender = User::factory()->create();
        $recipient = User::factory()->create();

        $this->actingAs($sender)->post(route('messages.store'), [
            'recipient_id' => $recipient->id,
            'body' => 'DB notification test',
        ]);

        $this->assertDatabaseHas('notifications', [
            'type' => NewMessageNotification::class,
            'notifiable_id' => $recipient->id,
        ]);
    }

    public function test_inbox_lists_each_partner_once(): void
    {
        $me = User::factory()->create();
        $alice = User::factory()->create();
        $bob = User::factory()->create();

        Message::create(['sender_id' => $alice->id, 'recipient_id' => $me->id, 'body' => 'Hi']);
        Message::create(['sender_id' => $bob->id, 'recipient_id' => $me->id, 'body' => 'Hey']);

        $response = $this->actingAs($me)->get(route('messages.index'));

        $response->assertSee($alice->name);
        $response->assertSee($bob->name);
    }

    public function test_inbox_shows_unread_count(): void
    {
        $me = User::factory()->create();
        $alice = User::factory()->create();

        Message::create(['sender_id' => $alice->id, 'recipient_id' => $me->id, 'body' => 'Unread 1']);
        Message::create(['sender_id' => $alice->id, 'recipient_id' => $me->id, 'body' => 'Unread 2']);

        $response = $this->actingAs($me)->get(route('messages.index'));

        $response->assertSee('(2)');
    }

    public function test_thread_shows_all_messages(): void
    {
        $me = User::factory()->create();
        $other = User::factory()->create();

        Message::create(['sender_id' => $other->id, 'recipient_id' => $me->id, 'body' => 'Hey']);
        Message::create(['sender_id' => $me->id, 'recipient_id' => $other->id, 'body' => 'Hi back']);

        $response = $this->actingAs($me)->get(route('messages.show', $other));

        $response->assertOk();
        $response->assertSee('Hey');
        $response->assertSee('Hi back');
    }

    public function test_thread_marks_messages_as_read(): void
    {
        $me = User::factory()->create();
        $other = User::factory()->create();

        $msg = Message::create(['sender_id' => $other->id, 'recipient_id' => $me->id, 'body' => 'Unread']);

        $this->actingAs($me)->get(route('messages.show', $other));

        $this->assertNotNull($msg->fresh()->read_at);
    }

    public function test_reply_uses_same_store_endpoint(): void
    {
        $me = User::factory()->create();
        $other = User::factory()->create();

        $this->actingAs($me)->post(route('messages.store'), [
            'recipient_id' => $other->id,
            'body' => 'Reply message',
        ]);

        $this->assertDatabaseHas('messages', [
            'sender_id' => $me->id,
            'recipient_id' => $other->id,
            'body' => 'Reply message',
        ]);
    }
}
