<?php

namespace Tests\Feature;

use App\Models\Tag;
use Tests\TestCase;
use App\Models\User;
use App\Models\Article;

class UpdateArticleTest extends TestCase
{
    /**
     * A basic feature test to update a article.
     */
    public function test_update_a_article(): void
    {
        $author = User::factory()->createOne();
        $article = Article::factory()->set('author_id', $author->id)->createOne();
        $tags = [];
        foreach($article->tags as $tag) {
            $tags[] = $tag->name;
        }
        $tags[] = 'colours';
        $tags[] = 'words';
        $data = [
            'title' => fake()->words(4, true),
            'content' => fake()->sentence(),
            'tags' => $tags,
        ];
        $response = $this->actingAs($author)->putJson(route('articles.update', $article->id), $data);

        $response->assertOk();

        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'content' => $data['content'],
            'author_id' => $article->author_id,
        ]);
    }

    /**
     * A basic feature test to check if error 403 forbidden is thrown if article to be updated isn't owned by a user.
     *
     * @return void
     */
    public function test_will_fail_with_a_403_if_article_to_be_updated_is_not_owned_by_the_user()
    {
        $myUser = User::factory()->createOne();
        $yourUser = User::factory()->createOne();
        $article = Article::factory()->set('author_id', $yourUser->id)->createOne();
        $response = $this->actingAs($myUser)->putJson(route('articles.update', $article->id));
        $response->assertStatus(403)->assertJsonStructure([
            'message',
        ]);
    }

    /**
     * A basic feature test to check if error 404 found is thrown if article to update doesn't exist.
     *
     * @return void
     */
    public function test_will_fail_with_a_404_if_article_to_be_updated_is_not_found()
    {
        $user = User::factory()->createOne();
        $article = Article::factory()->set('author_id', $user->id)->createOne();
        $response = $this->actingAs($user)->putJson(route('articles.update', 99999));
        $response->assertStatus(404);
        $this->assertDatabaseMissing('articles', [
            'id' => 99999,
        ]);
    }
}
