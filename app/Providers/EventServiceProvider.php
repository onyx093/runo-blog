<?php

namespace App\Providers;

use App\Events\UserFollowed;
use App\Events\ArticleCreated;
use App\Events\CommentCreated;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Listeners\SendUserFollowedNotifications;
use App\Listeners\SendArticleCreatedNotifications;
use App\Listeners\SendCommentCreatedNotifications;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        ArticleCreated::class => [
            SendArticleCreatedNotifications::class,
        ],
        CommentCreated::class => [
            SendCommentCreatedNotifications::class,
        ],
        UserFollowed::class => [
            SendUserFollowedNotifications::class,
        ],
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
