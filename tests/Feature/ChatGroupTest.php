<?php

namespace Tests\Feature;

use App\Models\ChatGroup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class ChatGroupTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testCreateChatGroup()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $response = $this->postJson('/api/create-group', [
            'name' => $this->faker->word,
        ]);

        $response->assertStatus(200)->assertJsonStructure(['chat_group_id']);
    }

    public function testListChatGroups()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $response = $this->getJson('/api/list-groups');

        $response->assertStatus(200)->assertJsonStructure(['chat_groups']);
    }

    public function testJoinChatGroup()
    {
        $chatGroup = ChatGroup::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->postJson("/api/join-group/{$chatGroup->id}", [
            'token' => $user->token,
        ]);

        $response->assertStatus(200)->assertJson(['message' => 'Successfully joined the group']);
    }

    public function testLeaveChatGroup()
    {
        $user = User::factory()->create();
        $chatGroup = ChatGroup::factory()->create();

        $chatGroup->users()->attach($user);

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->postJson("/api/leave-group/{$chatGroup->id}", [
            'token' => $user->token,
        ]);

        $response->assertStatus(200)->assertJson(['message' => 'Successfully left the group']);
    }
}
