<?php

namespace Tests\Feature;

use App\Models\Article;
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
        $article = Article::factory()->createOne();
        $content = 'foobar';

        $response = $this->postJson(
            route('comments.store'),
            [
                'content' => $content,
                'article_id' => $article->id,
                'author_id' => $author->id,
            ]
        );

        $response->assertCreated()->assertJsonIsObject()->assertJsonStructure(
            [
                'id',
                'article_id',
                'content',
                'created_at',
                'updated_at',
                'author_id',
            ]
        )->assertJson(
            [
                'article_id' => $article->id,
                'content' => $content,
                'author_id' => $author->id,
            ]
        );
    }

    /**
     * A basic feature test to check for required values while creating a comment.
     */
    public function test_throw_error_if_required_values_are_not_provided(): void
    {
        $author = User::factory()->createOne();
        $article = Article::factory()->createOne();
        $content = 'A new comment';

        $response = $this->postJson(
            route('comments.store'),
            [
                'article_id' => $article->id,
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
