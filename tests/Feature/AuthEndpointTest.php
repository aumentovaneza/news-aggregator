<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

class AuthEndpointTest extends TestCase
{
    use RefreshDatabase;

    // Test the login endpoint
    public function test_login()
    {
        // Arrange: Create a user
        $user = User::factory()->create();

        // Act: Send POST request to login endpoint with correct credentials
        $response = $this->postJson(route('auth.login'), [
            'email' => $user->email,
            'password' => 'password',  // assuming password is 'password'
        ]);

        // Assert: Check if the response is successful and contains a token
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['token']);
    }

    // Test the signup endpoint
    public function test_signup()
    {
        // Arrange: Define user data for signup
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        // Act: Send POST request to signup endpoint
        $response = $this->postJson(route('auth.signup'), $userData);

        // Assert: Check if the response is successful
        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(['token']);
    }

    // Test the logout endpoint
    public function test_logout()
    {
        // Arrange: Create and authenticate a user
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Act: Send POST request to logout endpoint
        $response = $this->postJson(route('auth.logout'));

        // Assert: Check if the response is successful
        $response->assertStatus(Response::HTTP_OK);
    }

    // Test the password reset endpoint
    public function test_password_reset()
    {
        // Arrange: Create and authenticate a user
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Act: Send PUT request to password reset endpoint
        $response = $this->putJson(route('auth.password.reset'), [
            'email' => $user->email,
            'currentPassword' => 'password',
            'newPasword' => 'newpassword',
        ]);

        // Assert: Check if the response is successful
        $response->assertStatus(Response::HTTP_OK);
    }
}
