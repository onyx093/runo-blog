<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\User;
use Tests\TestCase;

class StoreCommentTest extends TestCase
{
    /**
     * A basic feature test to store a comment.
     */
    public function test_comment_can_be_stored(): void
    {
        $author = User::factory()->createOne();
        $comment = Comment::factory()->set('author_id', $author->id)->makeOne();
        $content = 'foobar';

        $response = $this->actingAs($author)->postJson(
            route('comments.store'),
            [
                'content' => $content,
                'article_id' => $comment->article_id,
                'author_id' => $author->id,
            ]
        );

        $response->assertCreated()->assertJsonIsObject()->assertJsonStructure(
            [
                'id',
                'article_id',
                'author_id',
                'content',
                'created_at',
                'updated_at',
                'author_id',
            ]
        )->assertJson(
            [
                'article_id' => $comment->article_id,
                'content' => $content,
                'author_id' => $author->id,
            ]
        );
        $comments = Comment::all();
        $this->assertCount(1, $comments);
    }

    /**
     * A basic feature test to check for required values while creating a comment.
     */
    public function test_throw_error_if_required_values_are_not_provided(): void
    {
        $author = User::factory()->createOne();

        $response = $this->actingAs($author)->postJson(
            route('comments.store'),
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
