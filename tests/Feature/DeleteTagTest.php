<?php

namespace Tests\Feature;

use App\Models\Tag;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

class DeleteTagTest extends TestCase
{
    /**
     * A basic feature test to delete a tag.
     */
    public function test_delete_a_tag(): void
    {
        $author = User::factory()->createOne();
        $tag = Tag::factory()->set('author_id', $author->id)->createOne();
        $response = $this->actingAs($author)->deleteJson(route('tags.destroy', $tag->id));

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('tags', [
            'id' => $tag->id,
            'name' => $tag->name,
            'author_id' => $tag->author_id,
        ]);
    }

    /**
     * A basic feature test to check if error 403 forbidden is thrown if tag to be deleted isn't owned by a user.
     *
     * @return void
     */
    public function test_will_fail_with_a_403_if_tag_to_be_deleted_is_not_owned_by_the_user()
    {
        $myUser = User::factory()->createOne();
        $yourUser = User::factory()->createOne();
        $tag = Tag::factory()->set('author_id', $yourUser->id)->createOne();
        $response = $this->actingAs($myUser)->deleteJson(route('tags.destroy', $tag->id));
        $response->assertStatus(Response::HTTP_FORBIDDEN)->assertJsonStructure([
            'message',
        ]);
    }

    /**
     * A basic feature test to check if error 404 found is thrown if tag to delete doesn't exist.
     *
     * @return void
     */
    public function test_will_fail_with_a_404_if_tag_to_be_deleted_is_not_found()
    {
        $author = User::factory()->createOne();
        $tag = Tag::factory()->set('author_id', $author->id)->createOne();
        $response = $this->actingAs($author)->deleteJson(route('tags.show', 99999));
        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $this->assertDatabaseMissing('tags', [
            'id' => 99999,
        ]);
    }
}
