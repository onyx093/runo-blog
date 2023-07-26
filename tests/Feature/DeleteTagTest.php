<?php

namespace Tests\Feature;

use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteTagTest extends TestCase
{
    /**
     * A basic feature test to delete a tag.
     */
    public function test_delete_a_tag(): void
    {
        $tag = Tag::factory()->createOne();
        $response = $this->deleteJson(route('tags.destroy', $tag->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('tags', [
            'id' => $tag->id,
            'name' => $tag->name,
            'author_id' => $tag->author_id,
        ]);
    }

    /**
     * A basic feature test to check if error 404 found is thrown if tag to delete doesn't exist.
     *
     * @return void
     */
    public function test_will_fail_with_a_404_if_tag_to_be_deleted_is_not_found()
    {
        $tag = Tag::factory()->createOne();
        $data = [
            'content' => fake()->sentence(),
        ];
        $response = $this->putJson(route('tags.destroy', 99999), $data);
        $response->assertStatus(404);
        $this->assertDatabaseMissing('tags', [
            'id' => 99999,
        ]);
    }
}
