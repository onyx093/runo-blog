<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Comment;

class UpdateCommentTest extends TestCase
{
    /**
     * A basic feature test to update a comment.
     */
    public function test_update_a_comment(): void
    {
        $author = User::factory()->createOne();
        $comment = Comment::factory()->set('author_id', $author->id)->createOne();
        $data = [
            'content' => fake()->sentence(),
        ];
        $response = $this->actingAs($author)->putJson(route('comments.update', $comment->id), $data);

        $response->assertOk();

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'article_id' => $comment->article_id,
            'content' => $data['content'],
            'author_id' => $comment->author_id,
        ]);
    }

    /**
     * A basic feature test to check if error 403 forbidden is thrown if comment to be updated isn't owned by a user.
     *
     * @return void
     */
    public function test_will_fail_with_a_403_if_comment_to_be_updated_is_not_owned_by_the_user()
    {
        $myUser = User::factory()->createOne();
        $yourUser = User::factory()->createOne();
        $comment = Comment::factory()->set('author_id', $yourUser->id)->createOne();
        $response = $this->actingAs($myUser)->putJson(route('comments.update', $comment->id));
        $response->assertStatus(403)->assertJsonStructure([
            'message',
        ]);
    }

    /**
     * A basic feature test to check if error 404 found is thrown if comment to update doesn't exist.
     *
     * @return void
     */
    public function test_will_fail_with_a_404_if_comment_to_be_updated_is_not_found()
    {
        $author = User::factory()->createOne();
        $comment = Comment::factory()->set('author_id', $author->id)->createOne();
        $response = $this->actingAs($author)->putJson(route('comments.update', 99999));
        $response->assertStatus(404);
        $this->assertDatabaseMissing('comments', [
            'id' => 99999,
        ]);
    }
}
