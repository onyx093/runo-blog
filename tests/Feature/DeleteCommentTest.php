<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\User;
use Tests\TestCase;

class DeleteCommentTest extends TestCase
{
    /**
     * A basic feature test to delete a comment.
     */
    public function test_delete_a_comment(): void
    {
        $author = User::factory()->createOne();
        $comment = Comment::factory()->set('author_id', $author->id)->createOne();
        $response = $this->actingAs($author, 'api')->deleteJson(route('comments.destroy', $comment->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id,
            'article_id' => $comment->article_id,
            'content' => $comment->content,
            'author_id' => $comment->author_id,
        ]);
    }

    /**
     * A basic feature test to check if error 403 forbidden is thrown if comment to be deleted isn't owned by a user.
     *
     * @return void
     */
    public function test_will_fail_with_a_403_if_comment_to_be_deleted_is_not_owned_by_the_user()
    {
        $myUser = User::factory()->createOne();
        $yourUser = User::factory()->createOne();
        $comment = Comment::factory()->set('author_id', $yourUser->id)->createOne();
        $response = $this->actingAs($myUser, 'api')->deleteJson(route('comments.destroy', $comment->id));
        $response->assertStatus(403)->assertJsonStructure([
            'message',
        ]);
    }

    /**
     * A basic feature test to check if error 404 found is thrown if comment to delete doesn't exist.
     *
     * @return void
     */
    public function test_will_fail_with_a_404_if_comment_to_be_deleted_is_not_found()
    {
        $user = User::factory()->createOne();
        $comment = Comment::factory()->set('author_id', $user->id)->createOne();
        $response = $this->actingAs($user, 'api')->getJson(route('comments.show', 99999));
        $response->assertStatus(404);
        $this->assertDatabaseMissing('comments', [
            'id' => 99999,
        ]);
    }
}
