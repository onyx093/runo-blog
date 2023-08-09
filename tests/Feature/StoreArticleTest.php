<?php

namespace Tests\Feature;

use App\Interfaces\INotificationService;
use App\Models\Article;
use App\Models\Tag;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery;

class StoreArticleTest extends TestCase
{
    /**
     * A basic feature test to store a article without cover photo.
     */
    public function test_article_can_be_stored_without_cover_photo(): void
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
        );
        $articles = Article::all();
        $this->assertCount(1, $articles);
    }

    /**
     * A basic feature test to store a article with cover photo.
     */
    public function test_article_can_be_stored_with_cover_photo(): void
    {
        Storage::fake('public');
        $author = User::factory()->createOne();
        $tag = Tag::factory()->set('author_id', $author->id)->createOne();
        $tags = [$tag->name, 'colours', 'words'];
        $title = 'foobar';
        $content = 'An interesting foobar story';
        $cover_image = UploadedFile::fake()->image('cover_photo.png');

        $response = $this->actingAs($author)->postJson(
            route('articles.store'),
            [
                'title' => $title,
                'content' => $content,
                'tags' => $tags,
                'cover_photo' => $cover_image,
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
                'cover_url',
            ]
        );
        $articles = Article::all();
        $this->assertCount(1, $articles);

        Storage::disk('public')->assertExists('covers/' . $cover_image->hashName());
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

    /**
     * A basic feature test to check that twitter notifications were sent on article creation.
     */
    public function test_check_that_twitter_notifications_were_sent_on_article_creation(): void
    {
        $author = User::factory()->createOne();
        $tag = Tag::factory()->set('author_id', $author->id)->createOne();
        $tags = [$tag->name, 'colours', 'words'];
        $title = 'foobar';
        $content = 'An interesting foobar story';

        /** @var MockInterface */
        $twitterNotifier = Mockery::mock(INotificationService::class);
        $twitterNotifier->shouldReceive('notifyAbout')->once()->andReturn(true);
        $this->app->instance(INotificationService::class, [$twitterNotifier]);
        $this->actingAs($author)->postJson(
            route('articles.store'),
            [
                'title' => $title,
                'content' => $content,
                'tags' => $tags,
            ]
        );
    }

    /**
     * A basic feature test to check that moderator notifications were sent on article creation.
     */
    public function test_check_that_moderator_notifications_were_sent_on_article_creation(): void
    {
        $author = User::factory()->createOne();
        $tag = Tag::factory()->set('author_id', $author->id)->createOne();
        $tags = [$tag->name, 'colours', 'words'];
        $title = 'foobar';
        $content = 'An interesting foobar story';

        /** @var MockInterface */
        $moderatorNotifier = Mockery::mock(INotificationService::class);
        $moderatorNotifier->shouldReceive('notifyAbout')->once()->andReturn(true);
        $this->app->instance(INotificationService::class, [$moderatorNotifier]);
        $this->actingAs($author)->postJson(
            route('articles.store'),
            [
                'title' => $title,
                'content' => $content,
                'tags' => $tags,
            ]
        );
    }

    /**
     * A basic feature test to check that user notifications were sent on article creation.
     */
    public function test_check_that_user_notifications_were_sent_on_article_creation(): void
    {
        $author = User::factory()->createOne();
        $tag = Tag::factory()->set('author_id', $author->id)->createOne();
        $tags = [$tag->name, 'colours', 'words'];
        $title = 'foobar';
        $content = 'An interesting foobar story';

        /** @var MockInterface */
        $userNotifier = Mockery::mock(INotificationService::class);
        $userNotifier->shouldReceive('notifyAbout')->once()->andReturn(true);
        $this->app->instance(INotificationService::class, [$userNotifier]);
        $this->actingAs($author)->postJson(
            route('articles.store'),
            [
                'title' => $title,
                'content' => $content,
                'tags' => $tags,
            ]
        );
    }
}
