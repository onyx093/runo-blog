<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Comment;
use Illuminate\Testing\Fluent\AssertableJson;

class ViewCommentTest extends TestCase
{
    /**
     * A basic feature test to fetch all comments.
     */
    public function test_fetch_all_comments(): void
    {
        $comments = Comment::factory()->count(5)->create();

        $response = $this->getJson(route('comments.index'));

        $response->assertOk();
    }

    /**
     * A basic feature test to fetch a collection of paginated comments.
     *
     * @return void
     */
    public function test_fetch_collection_of_paginated_comments(): void
    {
        $comments = Comment::factory()->count(30)->create();

        $response = $this->getJson(route('comments.index'));

        $response->assertOk()
            ->assertJsonStructure([
                'current_page',
                'data' => [
                   '*' => ['id', 'content', 'created_at', 'updated_at']
                ],
                'first_page_url',
                'from',
                'last_page',
                'last_page_url',
                'links',
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to',
                'total',
            ]);
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
     * A basic feature test to check if a comment doesn't exist.
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
