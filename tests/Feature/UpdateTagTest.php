<?php

namespace Tests\Feature;

use App\Models\Tag;
use Tests\TestCase;

class UpdateTagTest extends TestCase
{
    /**
     * A basic feature test to update a tag.
     */
    public function test_update_a_tag(): void
    {
        $tag = Tag::factory()->createOne();
        $data = [
            'name' => fake()->sentence(),
        ];
        $response = $this->putJson(route('tags.update', $tag->id), $data);

        $response->assertStatus(204);

        $this->assertDatabaseHas('tags', [
            'id' => $tag->id,
            'name' => $data['name'],
            'author_id' => $tag->author_id,
        ]);
    }

    /**
     * A basic feature test to check if error 404 found is thrown if tag to update doesn't exist.
     *
     * @return void
     */
    public function test_will_fail_with_a_404_if_tag_to_be_updated_is_not_found()
    {
        $tag = Tag::factory()->createOne();
        $data = [
            'name' => fake()->sentence(),
        ];
        $response = $this->putJson(route('tags.update', 99999), $data);
        $response->assertStatus(404);
        $this->assertDatabaseMissing('tags', [
            'id' => 99999,
        ]);
    }
}
