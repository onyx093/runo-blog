<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ViewUserTest extends TestCase
{
    /**
     * A basic feature test to fetch user details with avatar.
     */
    public function test_fetch_user_details_with_avatar(): void
    {
        Storage::fake('public');
        $avatar = UploadedFile::fake()->image('my_avatar.png');
        $user = User::factory()
                    ->set('avatar_url', Storage::url('avatars/' . $avatar->hashName()))
                    ->createOne();

        $response = $this->getJson(route('users.show', $user->id));
        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('users', [
            'avatar_url' => asset(Storage::url('avatars/'. $avatar->hashName())),
        ]);

        /* $debugInfo = $response->decodeResponseJson()['debug-info'];

        $this->assertArrayHasKey('execution-time-milliseconds', $debugInfo, "No execution-time-milliseconds");
        $this->assertIsNumeric($debugInfo['execution-time-milliseconds']);

        $this->assertArrayHasKey('requested-get-parameters', $debugInfo, "No requested-get-parameters");
        $this->assertIsIterable($debugInfo['requested-get-parameters']);

        $this->assertArrayHasKey('requested-post-body', $debugInfo, "No requested-post-body");
        $this->assertIsIterable($debugInfo['requested-post-body']); */
    }

    /**
     * A basic feature test that a guest user can access the avatar of another user.
     */
    public function test_guest_user_can_access_another_user_avatar(): void
    {
        Storage::fake('public');
        $avatar = UploadedFile::fake()->image('my_avatar.png');
        $user = User::factory()
                    ->set('avatar_url', Storage::url('avatars/' . $avatar->hashName()))
                    ->count(6)
                    ->create();

        $response = $this->getJson(route('users.index'));
        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'current_page',
            'data' => [
                '*' => ['id', 'name', 'email', 'avatar_url' , 'created_at', 'updated_at']
            ],
            'first_page_url',
            'from',
            'last_page',
            'last_page_url',
            'links',
            'next_page_url',
            'path',
            'per_page',
            'prev_page_url',
            'to',
            'total',
        ]);

    }
}
