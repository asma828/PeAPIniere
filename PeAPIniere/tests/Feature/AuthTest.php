<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        User::query()->delete(); // Clear users before each test
    }

    
    public function test_register()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'role' => 'client',
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(201)
                 ->assertJsonStructure(['message', 'token']);
    }


    public function test_login()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => Hash::make('password123'),
            'role' => 'client',
        ]);

        $loginData = [
            'email' => $user->email,
            'password' => 'password123',
        ];


        $response = $this->postJson('/api/login', $loginData);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token', 'user']);
    }

    public function test_logout()
    {

        $user = User::create([
            'name' => 'Test User',
            'email' => 'logout.user@example.com',
            'password' => Hash::make('password123'),
            'role' => 'client',
        ]);


        $token = auth('api')->login($user);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/logout');

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Successfully logged out']);
    }

}