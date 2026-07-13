<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A valid registration payload including the required roll and phone.
     *
     * @return array<string, string>
     */
    private function payload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'roll' => '2207003',
            'phone' => '01700000000',
            'password' => 'password',
            'password_confirmation' => 'password',
        ], $overrides);
    }

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', $this->payload());

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_department_and_batch_are_saved_on_registration(): void
    {
        $this->post('/register', $this->payload([
            'department' => 'CSE',
            'batch' => '2022',
        ]));

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'department' => 'CSE',
            'batch' => '2022',
        ]);
    }

    public function test_registration_works_without_department_and_batch(): void
    {
        $response = $this->post('/register', $this->payload([
            'email' => 'test2@example.com',
            'roll' => '2207004',
            'phone' => '01700000001',
        ]));

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_roll_and_phone_are_required(): void
    {
        $response = $this->post('/register', $this->payload([
            'roll' => '',
            'phone' => '',
        ]));

        $response->assertSessionHasErrors(['roll', 'phone']);
        $this->assertGuest();
    }

    public function test_roll_must_be_unique(): void
    {
        User::factory()->create(['roll' => '2207003']);

        $response = $this->post('/register', $this->payload(['roll' => '2207003']));

        $response->assertSessionHasErrors('roll');
        $this->assertGuest();
    }

    public function test_phone_must_be_unique(): void
    {
        User::factory()->create(['phone' => '01700000000']);

        $response = $this->post('/register', $this->payload(['phone' => '01700000000']));

        $response->assertSessionHasErrors('phone');
        $this->assertGuest();
    }

    public function test_new_users_receive_20_free_credits(): void
    {
        $this->post('/register', $this->payload());

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'credits' => 20,
        ]);
    }
}
