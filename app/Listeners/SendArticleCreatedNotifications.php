<?php

namespace App\Listeners;

use App\Events\ArticleCreated;
use App\Notifications\NewArticleNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendArticleCreatedNotifications implements ShouldQueue
{

    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ArticleCreated $event): void
    {
        $followers = $event->article->author->followers;
        foreach ($followers as $follower) {
            $follower->notify(new NewArticleNotification($event->article));
        }
    }
}
