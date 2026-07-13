<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteUploaderTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_favorite_an_uploader(): void
    {
        $me = User::factory()->create();
        $uploader = User::factory()->create();

        $this->actingAs($me)
            ->post(route('users.favorite', $uploader))
            ->assertRedirect();

        $this->assertDatabaseHas('favorites', [
            'user_id' => $me->id,
            'uploader_id' => $uploader->id,
        ]);
        $this->assertTrue($me->fresh()->hasFavorited($uploader));
    }

    public function test_favoriting_twice_toggles_it_off(): void
    {
        $me = User::factory()->create();
        $uploader = User::factory()->create();

        $this->actingAs($me)->post(route('users.favorite', $uploader));
        $this->actingAs($me)->post(route('users.favorite', $uploader));

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $me->id,
            'uploader_id' => $uploader->id,
        ]);
    }

    public function test_a_user_cannot_favorite_themselves(): void
    {
        $me = User::factory()->create();

        $this->actingAs($me)
            ->post(route('users.favorite', $me))
            ->assertSessionHasErrors('favorite');

        $this->assertDatabaseCount('favorites', 0);
    }

    public function test_guests_cannot_favorite(): void
    {
        $uploader = User::factory()->create();

        $this->post(route('users.favorite', $uploader))
            ->assertRedirect(route('login'));
    }
}
