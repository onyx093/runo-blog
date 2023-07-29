<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Tests\TestCase;

class DeleteArticleTest extends TestCase
{
    /**
     * A basic feature test to delete a article.
     */
    public function test_delete_a_article(): void
    {
        $author = User::factory()->createOne();
        $article = Article::factory()->set('author_id', $author->id)->createOne();
        $response = $this->actingAs($author)->deleteJson(route('articles.destroy', $article->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('articles', [
            'id' => $article->id,
            'content' => $article->content,
            'author_id' => $article->author_id,
        ]);
    }

    /**
     * A basic feature test to check if error 403 forbidden is thrown if article to be deleted isn't owned by a user.
     *
     * @return void
     */
    public function test_will_fail_with_a_403_if_article_to_be_deleted_is_not_owned_by_the_user()
    {
        $myUser = User::factory()->createOne();
        $yourUser = User::factory()->createOne();
        $article = Article::factory()->set('author_id', $yourUser->id)->createOne();
        $response = $this->actingAs($myUser)->deleteJson(route('articles.destroy', $article->id));
        $response->assertStatus(403)->assertJsonStructure([
            'message',
        ]);
    }

    /**
     * A basic feature test to check if error 404 found is thrown if article to delete doesn't exist.
     *
     * @return void
     */
    public function test_will_fail_with_a_404_if_article_to_be_deleted_is_not_found()
    {
        $user = User::factory()->createOne();
        $article = Article::factory()->set('author_id', $user->id)->createOne();
        $response = $this->actingAs($user)->getJson(route('articles.show', 99999));
        $response->assertStatus(404);
        $this->assertDatabaseMissing('articles', [
            'id' => 99999,
        ]);
    }
}
