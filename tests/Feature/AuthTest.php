<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testUserRegistration()
    {
        $response = $this->postJson('/api/register', [
            'name' => $this->faker->userName,
        ]);

        $response->assertStatus(200)->assertJsonStructure(['token']);
    }

    public function testUserLogin()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/login', [
            'token' => 'DefaultToken',
        ]);

        $response->assertStatus(200)->assertJsonStructure(['token']);
    }
}
