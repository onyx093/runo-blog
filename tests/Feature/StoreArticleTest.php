<?php

namespace Tests\Feature;

use App\Models\Tag;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

class StoreArticleTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_article_can_be_stored(): void
    {
        $author = User::factory()->createOne();
        $tags = Tag::factory(2)->create(['author_id' => $author->id]);
        $tagsAsString = '';

        foreach($tags as $tag)
        {
            $tagsAsString .= $tag->name . ',';
        }
        $title = 'foobar';
        $content = 'An interesting foobar story';

        $response = $this->postJson(
            route('articles.store'),
            [
                'title' => $title,
                'content' => $content,
                'tags' => $tagsAsString,
                'author_id' => $author->id,
            ]
        );

        $response->assertCreated()->assertJsonIsObject()->assertJsonStructure(
            [
                'id',
                'title',
                'content',
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

    public function test_throw_error_if_required_values_are_not_provided(): void
    {
        $author = User::factory()->createOne();
        $tags = Tag::factory(2)->create(['author_id' => $author->id]);
        $tagsAsString = '';

        foreach($tags as $tag)
        {
            $tagsAsString .= $tag->name . ',';
        }
        $title = 'foobar';

        $response = $this->postJson(
            route('articles.store'),
            [
                'title' => $title,
                'author_id' => $author->id,
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
