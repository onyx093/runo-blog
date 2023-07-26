<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class StoreTagTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_comment_can_be_stored(): void
    {
        $author = User::factory()->createOne();
        $name = 'foobar';

        $response = $this->postJson(
            route('tags.store'),
            [
                'name' => $name,
                'author_id' => $author->id,
            ]
        );

        $response->assertCreated()->assertJsonIsObject()->assertJsonStructure(
            [
                'id',
                'name',
                'created_at',
                'updated_at',
                'author_id',
            ]
        )->assertJson(
            [
                'name' => $name,
                'author_id' => $author->id,
            ]
        );
    }

    public function test_throw_error_if_required_values_are_not_provided(): void
    {
        $author = User::factory()->createOne();

        $response = $this->postJson(
            route('comments.store'),
            [
                'author_id' => $author->id,
            ]
        );

        $response->assertUnprocessable()->assertJsonStructure(
            [
                'message',
                'errors',
            ]
        );
    }
}
