<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();
        // Run the seeder to create default user roles
        $this->artisan('db:seed');
    }

    public function test_users_can_authenticate(): void
    {
        $user = User::factory()->create();
        //Here new users are clients only. No admin or other roles avaialble
        $user->assignRole('client');

        $response = $this->postJson('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertNoContent();
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();
        //Here new users are clients only. No admin or other roles avaialble
        $user->assignRole('client');

        $this->postJson('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();
        //Here new users are clients only. No admin or other roles avaialble
        $user->assignRole('client');

        $response = $this->actingAs($user)->postJson('/logout');

        $this->assertGuest();
        $response->assertNoContent();
    }
}
