<?php

namespace Tests\Feature;

use App\Models\Tag;
use Tests\TestCase;

class ViewTagTest extends TestCase
{
    /**
     * A basic feature test to fetch all tags.
     */
    public function test_fetch_all_tags(): void
    {
        $tags = Tag::factory()->count(5)->create();

        $response = $this->getJson(route('tags.index'));

        $response->assertOk()->assertJsonIsObject();
    }

    /**
     * A basic feature test to fetch a tag.
     */
    public function test_fetch_a_tag(): void
    {
        $tag = Tag::factory()->createOne();

        $response = $this->getJson(route('tags.show', $tag->id));

        $response->assertOk()->assertJsonStructure([
            'id',
            'author_id',
            'name',
            'created_at',
            'updated_at',
        ]);
    }

    /**
     * A basic feature test to check if a tag that doesn't exist.
     */
    public function test_should_an_error_when_fetching_a_tag_that_does_not_exist(): void
    {
        Tag::factory()->createOne();

        $response = $this->getJson(route('tags.show', 99999));

        $response->assertNotFound();

        $this->assertDatabaseMissing('tags', [
            'id' => 9999,
        ]);
    }
}
