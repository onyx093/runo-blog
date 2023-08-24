<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UnfollowUserTest extends TestCase
{
    /**
     * A basic feature test to check that an authenticated user can unfollow another user.
     */
    public function test_authenticated_user_can_unfollow_another_user(): void
    {
        $user = User::factory()->createOne();
        $follower = User::factory()->createOne();
        $follower->follow($user->id);

        $followers = $user->followers;
        $this->assertCount(1, $followers);

        $response = $this->actingAs($follower)->postJson(route('user.unfollow', $user));

        $response->assertStatus(Response::HTTP_OK);

        $follows = $follower->follows;
        $this->assertCount(0, $follows);
    }

    /**
     * A basic feature test to check that an authenticated user cannot unfollow another user that they are not following.
     */
    public function test_authenticated_user_cannot_unfollow_another_user_not_followed(): void
    {
        $user = User::factory()->createOne();
        $follower = User::factory()->createOne();
        $response = $this->actingAs($follower)->postJson(route('user.unfollow', $user));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * A basic feature test to check that an unauthenticated user cannot unfollow another user.
     */
    public function test_unauthenticated_user_cannot_unfollow_another_user(): void
    {
        $user = User::factory()->createOne();
        $follower = User::factory()->createOne();
        $response = $this->postJson(route('user.unfollow', $user));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
