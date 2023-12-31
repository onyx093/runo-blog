<?php

namespace Tests\Feature;

use App\Events\CommentCreated;
use App\Listeners\SendCommentCreatedNotifications;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\Event;
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
     * A basic feature test to check that events, listeners and notifications are fired on comment creation.
     */
    public function test_events_listeners_and_notifications_are_fired_on_comment_creation(): void
    {
        Event::fake();

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

        $comment = Comment::where('content', $content)->first();
        Event::assertDispatched(function (CommentCreated $event) use ($comment) {
            return $event->comment->id === $comment->id;
        });

        Event::assertListening(
            CommentCreated::class,
            SendCommentCreatedNotifications::class
        );
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
