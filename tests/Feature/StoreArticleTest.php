<?php

namespace Tests\Feature;

use App\Models\Tag;
use Tests\TestCase;
use App\Models\User;

class StoreArticleTest extends TestCase
{
    /**
     * A basic feature test to store a article.
     */
    public function test_article_can_be_stored(): void
    {
        $author = User::factory()->createOne();
        $tag = Tag::factory()->set('author_id', $author->id)->createOne();
        $tags = [$tag->name, 'colours', 'words'];
        $title = 'foobar';
        $content = 'An interesting foobar story';

        $response = $this->actingAs($author)->postJson(
            route('articles.store'),
            [
                'title' => $title,
                'content' => $content,
                'tags' => $tags,
            ]
        );

        $response->assertCreated()->assertJsonIsObject()->assertJsonStructure(
            [
                'id',
                'title',
                'content',
                'author_id',
                'created_at',
                'updated_at',
                'author_id',
            ]
        )/* ->assertJson(function (AssertableJson $json) use ($title) {
            // dd($json);
            $json->has('debug-info')
                ->where('debug-info.execution-time-milliseconds', function ($time) {
                    return $time >= 0;
                })
                ->where('debug-info.requested-get-parameters', [])
                ->where('debug-info.requested-post-body', [
                    "title" => $title,
                ]);
            }) */;
    }

    /**
     * A basic feature test to check for required values while creating a article.
     */
    public function test_throw_error_if_required_values_are_not_provided(): void
    {
        $author = User::factory()->createOne();
        $tag = Tag::factory()->set('author_id', $author->id)->createOne();
        $tags = [$tag->name, 'colours', 'words'];

        $response = $this->actingAs($author)->postJson(
            route('articles.store'),
            [
                'tags' => $tags
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
