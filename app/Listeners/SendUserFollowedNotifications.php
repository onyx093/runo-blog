<?php

namespace App\Listeners;

use App\Events\UserFollowed;
use App\Notifications\NewFollowerNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendUserFollowedNotifications implements ShouldQueue
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
    public function handle(UserFollowed $event): void
    {
        $event->user->notify(new NewFollowerNotification($event->follower, $event->user));
    }
}
