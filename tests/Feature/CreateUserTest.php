<?php

namespace Tests\Feature;

use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    /**
     * A basic feature test to create a new user without avatar.
     */
    public function test_can_register_a_new_user_without_avatar(): void
    {
        // given
        Storage::fake('public');

        $new_user_data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
        ];

        // when
        $response = $this->postJson(route('register'), $new_user_data);

        // then
        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure(['token']);

        $this->assertDatabaseHas('users', [
            'name' => $new_user_data['name'],
            'email' => $new_user_data['email'],
        ]);

        Storage::disk('public')->assertDirectoryEmpty('/avatars');
    }

    /**
     * A basic feature test to create a new user with avatar.
     */
    public function test_can_register_a_new_user_with_avatar(): void
    {
        // given
        Storage::fake('public');

        $new_user_data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'avatar' => UploadedFile::fake()->image('john_avatar.jpg'),
        ];

        // when
        $response = $this->postJson(route('register'), $new_user_data);

        // then
        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure(['token']);

        $this->assertDatabaseHas('users', [
            'name' => $new_user_data['name'],
            'email' => $new_user_data['email'],
        ]);

        Storage::disk('public')->assertExists('avatars/' . $new_user_data['avatar']->hashName());
    }

    /**
     * A basic feature test to check that only an image file can be uploaded as new user avatar.
     */
    public function test_can_only_upload_an_image_file_for_new_user_avatar(): void
    {
        // given, testing for pdf file
        Storage::fake('public');

        $new_user_data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'avatar' => UploadedFile::fake()->create('john_avatar.pdf', 100, 'application/pdf'),
        ];

        // when
        $response = $this->postJson(route('register'), $new_user_data);

        // then
        $response->assertUnprocessable()->assertJsonValidationErrors(['avatar']);

        // given, testing for audio file
        $new_user_data['avatar'] = UploadedFile::fake()->create('john_avatar.mp3', 100, 'audio/mpeg');

        // when
        $response = $this->postJson(route('register'), $new_user_data);

        // then
        $response->assertUnprocessable()->assertJsonValidationErrors(['avatar']);

        $this->assertDatabaseMissing('users', [
            'name' => $new_user_data['name'],
            'email' => $new_user_data['email'],
        ]);
    }
}
