<?php

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

/**
 * A basic feature test to filter comments by id of an article.
 */
test('can filter comments according to article_id', function () {
    // given
    $article1 = Article::factory()
        ->has(Comment::factory(2), 'comments')
        ->createOne();

    $article2 = Article::factory()
        ->has(Comment::factory(2), 'comments')
        ->createOne();

    $this->assertDatabaseCount('comments', 4);

    // when
    $response = $this->getJson(
        route(
            'comments.index',
            ['article_id' => $article1->id]
        )
    );

    // then
    $response->assertStatus(200);
    $response->assertJson(function (AssertableJson $json) {
        $json
            ->count('data', 2)
            ->etc();
    });
});

/**
 * A basic feature test to filter comments by author.
 */
test('can filter comments by author', function () {
    // given
    $author1 = User::factory()->set('email', 'pest1@example.com')->createOne();
    $articles1 = Article::factory()
        ->set('author_id', $author1->id)
        ->has(Comment::factory(2)->set('author_id', $author1->id), 'comments')
        ->createOne();

    $author2 = User::factory()->set('email', 'pest2@example.com')->createOne();
    $articles2 = Article::factory()
        ->set('author_id', $author2->id)
        ->has(Comment::factory(2)->set('author_id', $author2->id), 'comments')
        ->createOne();

    $this->assertDatabaseCount('comments', 4);

    // when
    $response = $this->getJson(
        route(
            'comments.index',
            ['author_email' => $author1->email]
        )
    );

    // then
    $response->assertStatus(200);
    $response->assertJson(function (AssertableJson $json) {
        $json
            ->count('data', 2)
            ->etc();
    });
});

/**
 * A basic feature test to filter comments by a search string.
 */
test('can filter comments according to a search string', function () {
    // given
    $article1 = Article::factory()
        ->has(Comment::factory()->count(3)->set('content', 'olaleye'), 'comments')
        ->createOne();

    $article2 = Article::factory()
        ->has(Comment::factory(2), 'comments')
        ->createOne();

    $this->assertDatabaseCount('comments', 5);

    // when
    $response = $this->getJson(
        route(
            'comments.index',
            ['search' => 'olaleye']
        )
    );

    // then
    $response->assertStatus(200);
    $response->assertJson(function (AssertableJson $json) {
        $json
            ->count('data', 3)
            ->etc();
    });
});

test('nested article comments route returns correct data', function () {
    // given
    $article1 = Article::factory()
        ->has(Comment::factory(2), 'comments')
        ->createOne();

    $article2 = Article::factory()
        ->has(Comment::factory(2), 'comments')
        ->createOne();

    $this->assertDatabaseCount('comments', 4);

    // when
    $response = $this->getJson(
        route(
            'articles.comments.show',
            $article1
        )
    );

    // then
    $response->assertStatus(200);
    $response->assertJson(
        fn (AssertableJson $json) =>
        $json
            ->has('data', 2)
            ->has(
                'data.0',
                fn (AssertableJson $data) =>
                $data
                    ->where('id', $article1->comments[0]->id)
                    ->etc()
            )
            ->has(
                'data.1',
                fn (AssertableJson $data) =>
                $data
                    ->where('id', $article1->comments[1]->id)
                    ->etc()
            )
            ->etc()
    );
});
