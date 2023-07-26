<?php

namespace Tests\Feature;

use App\Models\Article;
use Tests\TestCase;

class UpdateArticleTest extends TestCase
{
    /**
     * A basic feature test to update a article.
     */
    public function test_update_a_article(): void
    {
        $article = Article::factory()->createOne();
        $data = [
            'title' => fake()->words(4, true),
            'content' => fake()->sentence(),
        ];
        $response = $this->putJson(route('articles.update', $article->id), $data);

        $response->assertStatus(204);

        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'content' => $data['content'],
            'author_id' => $article->author_id,
        ]);
    }

    /**
     * A basic feature test to check if error 404 found is thrown if article to update doesn't exist.
     *
     * @return void
     */
    public function test_will_fail_with_a_404_if_article_to_be_updated_is_not_found()
    {
        $article = Article::factory()->createOne();
        $data = [
            'title' => fake()->words(4, true),
            'content' => fake()->sentence(),
        ];
        $response = $this->putJson(route('articles.update', 99999), $data);
        $response->assertStatus(404);
        $this->assertDatabaseMissing('articles', [
            'id' => 99999,
        ]);
    }
}
