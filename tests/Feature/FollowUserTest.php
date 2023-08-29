<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class FollowUserTest extends TestCase
{
    /**
     * A basic feature test to check that an authenticated user can follow another user.
     */
    public function test_authenticated_user_can_follow_another_user(): void
    {
        $user = User::factory()->createOne();
        $follower = User::factory()->createOne();
        $response = $this->actingAs($follower)->postJson(route('user.follow', $user));

        $response->assertStatus(Response::HTTP_OK);

        $followers = $user->followers;
        $this->assertCount(1, $followers);
    }

    /**
     * A basic feature test to check that an authenticated user cannot follow himself.
     */
    public function test_authenticated_user_cannot_follow_himself(): void
    {
        $user = User::factory()->createOne();
        $response = $this->actingAs($user)->postJson(route('user.follow', $user));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $followers = $user->followers;
        $this->assertCount(0, $followers);
    }

    /**
     * A basic feature test to check that an unauthenticated user cannot follow another user.
     */
    public function test_unauthenticated_user_cannot_follow_another_user(): void
    {
        $user = User::factory()->createOne();
        $follower = User::factory()->createOne();
        $response = $this->postJson(route('user.follow', $user));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
