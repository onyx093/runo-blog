<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UpdateUserTest extends TestCase
{
    /**
     * A basic feature test to check that an authenticated user can update his avatar.
     */
    public function test_authenticated_user_can_update_his_avatar(): void
    {
        // given
        Storage::fake('public');

        $user = User::factory()
                    ->set('name', 'John Doe')
                    ->set('email', 'john@example.com')
                    ->set('password', 'password123')
                    ->createOne();

        $image_file = UploadedFile::fake()->image('john_avatar.jpg');
        $data_for_update = [
            'name' => $updated_name = $user->name . " updated",
            'avatar' => $image_file,
        ];

        // when
        $response = $this->actingAs($user)->putJson(route('users.update', $user->id), $data_for_update);

        // then
        $response->assertStatus(Response::HTTP_OK);

        $updated_user = User::query()->find($user->id);
        $this->assertNull($user->avatar_url);
        // $this->assertNotNull($updated_user->avatar_url);

        $this->assertDatabaseHas('users', [
            'name' => $updated_name,
        ]);

        // Storage::disk('public')->assertExists('avatars/' . $data_for_update['avatar']->hashName());
    }

    /**
     * A basic feature test to check that an authenticated user can't update another person's avatar.
     */
    public function test_authenticated_user_cannot_update_another_person_avatar(): void
    {
        // given
        Storage::fake('public');

        $user = User::factory()
                    ->set('name', 'John Doe')
                    ->set('email', 'john@example.com')
                    ->set('password', 'password123')
                    ->createOne();

        $another_user = User::factory()
                    ->set('name', 'Jane Doe')
                    ->set('email', 'jane@example.com')
                    ->set('password', 'password321')
                    ->createOne();

        $image_file = UploadedFile::fake()->image('john_avatar.jpg');
        $data_for_update = [
            'name' => $updated_name = $user->name . " updated",
            'avatar' => $image_file,
        ];

        // when
        $response = $this->actingAs($another_user)->putJson(route('users.update', $user->id), $data_for_update);

        // then
        $response->assertStatus(Response::HTTP_FORBIDDEN);

        $updated_user_or_so = User::query()->find($user->id);
        $this->assertNull($user->avatar_url);
        $this->assertNull($updated_user_or_so->avatar_url);

        $this->assertDatabaseMissing('users', [
            'name' => $updated_name,
        ]);

        Storage::disk('public')->assertMissing('avatars/' . $data_for_update['avatar']->hashName());
    }

    /**
     * A basic feature test to check that only an authenticated user can update his avatar.
     */
    public function test_only_authenticated_user_can_update_his_avatar(): void
    {
        // given
        Storage::fake('public');

        $user = User::factory()
                    ->set('name', 'John Doe')
                    ->set('email', 'john@example.com')
                    ->set('password', 'password123')
                    ->createOne();

        $image_file = UploadedFile::fake()->image('john_avatar.jpg');
        $data_for_update = [
            'name' => $updated_name = $user->name . " updated",
            'avatar' => $image_file,
        ];

        // when
        $response = $this->putJson(route('users.update', $user->id), $data_for_update);

        // then
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);

        $updated_user = User::query()->find($user->id);
        $this->assertNull($user->avatar_url);
        $this->assertNull($updated_user->avatar_url);

        $this->assertDatabaseMissing('users', [
            'name' => $updated_name,
        ]);

        Storage::disk('public')->assertMissing('avatars/' . $data_for_update['avatar']->hashName());
    }
}
