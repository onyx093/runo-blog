<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Article;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraph(),
            'author_id' => User::factory(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Article $article) {
            Tag::factory()->count(2)->create();
            $tags = Tag::inRandomOrder()->take(rand(1, 3))->pluck('id');
            $article->tags()->sync($tags);
        });
    }

}
