<?php

namespace Tests\Feature;

use App\Models\Article;
use Tests\TestCase;

class ViewArticleTest extends TestCase
{
    /**
     * A basic feature test to fetch all articles.
     */
    public function test_fetch_all_articles(): void
    {
        $articles = Article::factory()->count(5)->create();

        $response = $this->getJson(route('articles.index'));

        $response->assertOk()->assertJsonIsObject();
    }

    /**
     * A basic feature test to fetch a article.
     */
    public function test_fetch_a_article(): void
    {
        $article = Article::factory()->createOne();

        $response = $this->getJson(route('articles.show', $article->id));

        $response->assertOk()->assertJsonStructure([
            'id',
            'author_id',
            'title',
            'content',
            'created_at',
            'updated_at',
        ]);
    }

    /**
     * A basic feature test to check if a article that doesn't exist.
     */
    public function test_should_an_error_when_fetching_a_article_that_does_not_exist(): void
    {
        Article::factory()->createOne();

        $response = $this->getJson(route('articles.show', 99999));

        $response->assertNotFound();

        $this->assertDatabaseMissing('articles', [
            'id' => 9999,
        ]);
    }
}
