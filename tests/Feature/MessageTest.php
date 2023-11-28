<?php

namespace Tests\Feature;

use App\Models\ChatGroup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class MessageTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testSendMessage()
    {
        $user = User::factory()->create();
        $chatGroup = ChatGroup::factory()->create();
        $chatGroup->users()->attach($user);

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->postJson("/api/send/{$chatGroup->id}", [
            'token' => $user->token,
            'content' => $this->faker->sentence,
        ]);

        $response->assertStatus(200)->assertJsonStructure(['message_id']);
    }

    public function testListMessages()
    {
        $user = User::factory()->create();
        $chatGroup = ChatGroup::factory()->create();
        $chatGroup->users()->attach($user);

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->getJson("/api/list/{$chatGroup->id}");

        $response->assertStatus(200)->assertJsonStructure(['messages']);
    }
}
