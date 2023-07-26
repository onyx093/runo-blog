<?php

namespace Tests\Feature;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteArticleTest extends TestCase
{
    /**
     * A basic feature test to delete a article.
     */
    public function test_delete_a_article(): void
    {
        $article = Article::factory()->createOne();
        $response = $this->deleteJson(route('articles.destroy', $article->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('articles', [
            'id' => $article->id,
            'content' => $article->content,
            'author_id' => $article->author_id,
        ]);
    }

    /**
     * A basic feature test to check if error 404 found is thrown if article to delete doesn't exist.
     *
     * @return void
     */
    public function test_will_fail_with_a_404_if_article_to_be_deleted_is_not_found()
    {
        $article = Article::factory()->createOne();
        $response = $this->deleteJson(route('articles.destroy', 99999));
        $response->assertStatus(404);
        $this->assertDatabaseMissing('articles', [
            'id' => 99999,
        ]);
    }
}
