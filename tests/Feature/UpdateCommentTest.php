<?php

namespace Tests\Feature;

use App\Models\Comment;
use Tests\TestCase;

class UpdateCommentTest extends TestCase
{
    /**
     * A basic feature test to update a comment.
     */
    public function test_update_a_comment(): void
    {
        $comment = Comment::factory()->createOne();
        $data = [
            'content' => fake()->sentence(),
        ];
        $response = $this->putJson(route('comments.update', $comment->id), $data);

        $response->assertStatus(204);

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'article_id' => $comment->article_id,
            'content' => $data['content'],
            'author_id' => $comment->author_id,
        ]);
    }

    /**
     * A basic feature test to check if error 404 found is thrown if comment to update doesn't exist.
     *
     * @return void
     */
    public function test_will_fail_with_a_404_if_comment_to_be_updated_is_not_found()
    {
        $comment = Comment::factory()->createOne();
        $data = [
            'content' => fake()->sentence(),
        ];
        $response = $this->putJson(route('comments.update', 99999), $data);
        $response->assertStatus(404);
        $this->assertDatabaseMissing('comments', [
            'id' => 99999,
        ]);
    }
}
