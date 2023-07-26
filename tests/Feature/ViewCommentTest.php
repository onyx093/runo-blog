<?php

namespace Tests\Feature;

use App\Models\Comment;
use Tests\TestCase;

class ViewCommentTest extends TestCase
{
    /**
     * A basic feature test to fetch all comments.
     */
    public function test_fetch_all_comments(): void
    {
        $comments = Comment::factory()->count(5)->create();

        $response = $this->getJson(route('comments.index'));

        $response->assertOk()->assertJsonIsObject();
    }

    /**
     * A basic feature test to fetch a comment.
     */
    public function test_fetch_a_comment(): void
    {
        $comment = Comment::factory()->createOne();

        $response = $this->getJson(route('comments.show', $comment->id));

        $response->assertOk()->assertJsonStructure([
            'id',
            'article_id',
            'author_id',
            'content',
            'created_at',
            'updated_at',
        ]);
    }

    /**
     * A basic feature test to check if a comment that doesn't exist.
     */
    public function test_should_an_error_when_fetching_a_comment_that_does_not_exist(): void
    {
        Comment::factory()->createOne();

        $response = $this->getJson(route('comments.show', 99999));

        $response->assertNotFound();

        $this->assertDatabaseMissing('comments', [
            'id' => 9999,
        ]);
    }
}
