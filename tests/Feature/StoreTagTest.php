<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class StoreTagTest extends TestCase
{
    /**
     * A basic feature test to store a tag.
     */
    public function test_tag_can_be_stored(): void
    {
        $author = User::factory()->createOne();
        $name = 'foobar';

        $response = $this->actingAs($author, 'api')->postJson(
            route('tags.store'),
            [
                'name' => $name,
            ]
        );

        $response->assertCreated()->assertJsonIsObject()->assertJsonStructure(
            [
                'id',
                'author_id',
                'name',
                'created_at',
                'updated_at',
            ]
        )->assertJson(
            [
                'name' => $name,
                'author_id' => $author->id,
            ]
        );
    }

    /**
     * A basic feature test to check for required values while creating a tag.
     */
    public function test_throw_error_if_required_values_are_not_provided(): void
    {
        $author = User::factory()->createOne();

        $response = $this->actingAs($author, 'api')->postJson(
            route('tags.store'),
            []
        );

        $response->assertUnprocessable()->assertJsonStructure(
            [
                'message',
                'errors',
            ]
        );
    }
}
