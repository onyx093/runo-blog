<?php

use App\Models\User;
use App\Models\Article;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;

/**
 * A basic feature test to filter articles by id of the author.
 */
test('can filter articles according to author_id', function () {
    // given
    $user1 = User::factory()
        ->has(Article::factory(2), 'articles')
        ->createOne();

    $user2 = User::factory()
        ->has(Article::factory(2), 'articles')
        ->createOne();

    $this->assertDatabaseCount('articles', 4);

    // when
    $response = $this->getJson(
        route(
            'articles.index',
            ['author_id' => $user1->id]
        )
    );

    // then
    $response->assertStatus(Response::HTTP_OK);
    $response->assertJson(function (AssertableJson $json) {
        $json
            ->count('data', 2)
            ->etc();
    });
});
