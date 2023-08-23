<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Tag;
use App\Models\User;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $users = User::factory(7)->create()->each( function($user) {

            Tag::factory()->create([
                'author_id' => $user->id,
            ]);
        });

        $tags = Tag::all();

        $users->map(function($user) use ($tags, $users){
            Article::factory(rand(3, 7))->create([
                'author_id' => $user->id,
            ])->each(function($article) use ($tags, $users){

                $article->tags()->attach($tags->random(rand(1, 2)));

                Comment::factory(rand(2, 5))->create([
                    'article_id' => $article->id,
                    'author_id' => $users->random(),
                ]);
            });
        });

        $defaultUser = User::factory()
                            ->set('email', 'test@example.com')
                            ->set('password', 'password123')
                            ->has(Article::factory(4))
                            ->createOne();

        $defaultUser->articles()->each(function($article) use ($tags, $users) {
            $article->tags()->attach($tags->random(rand(1, 2)));

            Comment::factory()->count(3)->set('article_id', $article->id)->set('author_id', $users->random()->id);
        });

        $allUsers = User::all();
        $allUsers->each(function($user) use ($allUsers) {
            $user->follows()->attach($allUsers->toQuery()->where('id', '!=', $user->id)->get()->random(rand(1, 4)));
        });
    }
}
