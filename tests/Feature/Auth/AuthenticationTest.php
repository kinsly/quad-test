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

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);

    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();
        //Here new users are clients only. No admin or other roles avaialble
        $user->assignRole('client');

        $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }
}
