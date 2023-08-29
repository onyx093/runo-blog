<?php

namespace Tests\Feature;

use App\Models\Tag;
use Tests\TestCase;
use App\Models\User;
use App\Models\Article;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UpdateArticleTest extends TestCase
{
    /**
     * A basic feature test to update a article without a cover photo.
     */
    public function test_update_a_article_without_a_cover_photo(): void
    {
        $author = User::factory()->createOne();
        $article = Article::factory()->set('author_id', $author->id)->createOne();
        $tags = [];
        foreach($article->tags as $tag) {
            $tags[] = $tag->name;
        }
        $tags[] = 'colours';
        $tags[] = 'words';
        $title = fake()->words(4, true);
        $slug = Str::slug($title);
        $data = [
            'title' => $title,
            'slug' => $slug,
            'content' => fake()->sentence(),
            'tags' => $tags,
        ];
        $response = $this->actingAs($author)->putJson(route('articles.update', $article->id), $data);

        $response->assertOk();

        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'title' => $data['title'],
            'slug' => $data['slug'],
            'content' => $data['content'],
            'author_id' => $article->author_id,
        ]);
    }

    /**
     * A basic feature test to update a article with a cover photo.
     */
    public function test_update_a_article_with_a_cover_photo(): void
    {
        Storage::fake('public');
        $author = User::factory()->createOne();
        $article = Article::factory()->set('author_id', $author->id)->createOne();
        $tags = [];
        foreach($article->tags as $tag) {
            $tags[] = $tag->name;
        }
        $tags[] = 'colours';
        $tags[] = 'words';
        $cover_image = UploadedFile::fake()->image('cover_photo.jpg');
        $title = fake()->words(4, true);
        $slug = Str::slug($title);
        $data = [
            'title' => $title,
            'slug' => $slug,
            'content' => fake()->sentence(),
            'tags' => $tags,
            'cover_photo' => $cover_image,
        ];
        $response = $this->actingAs($author)->putJson(route('articles.update', $article->id), $data);

        $response->assertOk();

        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'title' => $data['title'],
            'slug' => $data['slug'],
            'content' => $data['content'],
            'author_id' => $article->author_id,
            'cover_url' => asset(Storage::url('covers/' . $cover_image->hashName())),
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
        $response->assertStatus(Response::HTTP_FORBIDDEN)->assertJsonStructure([
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
        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $this->assertDatabaseMissing('articles', [
            'id' => 99999,
        ]);
    }
}
