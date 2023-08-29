<?php

namespace Tests\Feature;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class UpdateTagTest extends TestCase
{
    /**
     * A basic feature test to update a tag.
     */
    public function test_update_a_tag(): void
    {
        $author = User::factory()->createOne();
        $tag = Tag::factory()->set('author_id', $author->id)->createOne();
        $data = [
            'name' => fake()->sentence(),
        ];
        $response = $this->actingAs($author)->putJson(route('tags.update', $tag->id), $data);

        $response->assertOk();

        $this->assertDatabaseHas('tags', [
            'id' => $tag->id,
            'name' => $data['name'],
            'author_id' => $tag->author_id,
        ]);
    }

    /**
     * A basic feature test to check if error 403 forbidden is thrown if tag to be updated isn't owned by a user.
     *
     * @return void
     */
    public function test_will_fail_with_a_403_if_tag_to_be_updated_is_not_owned_by_the_user()
    {
        $myUser = User::factory()->createOne();
        $yourUser = User::factory()->createOne();
        $tag = Tag::factory()->set('author_id', $yourUser->id)->createOne();
        $response = $this->actingAs($myUser)->putJson(route('tags.update', $tag->id));
        $response->assertStatus(Response::HTTP_FORBIDDEN)->assertJsonStructure([
            'message',
        ]);
    }

    /**
     * A basic feature test to check if error 404 found is thrown if tag to update doesn't exist.
     *
     * @return void
     */
    public function test_will_fail_with_a_404_if_tag_to_be_updated_is_not_found()
    {
        $author = User::factory()->createOne();
        $tag = Tag::factory()->set('author_id', $author->id)->createOne();
        $response = $this->actingAs($author)->putJson(route('tags.update', 99999));
        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $this->assertDatabaseMissing('tags', [
            'id' => 99999,
        ]);
    }
}
