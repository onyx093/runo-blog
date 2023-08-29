<?php

use App\Models\User;
use App\Models\Tag;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;

/**
 * A basic feature test to filter tags by id of the author.
 */
test('can filter tags according to author_id', function () {
    // given
    $user1 = User::factory()
        ->has(Tag::factory(2), 'tags')
        ->createOne();

    $user2 = User::factory()
        ->has(Tag::factory(2), 'tags')
        ->createOne();

    $this->assertDatabaseCount('tags', 4);

    // when
    $response = $this->getJson(
        route(
            'tags.index',
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
