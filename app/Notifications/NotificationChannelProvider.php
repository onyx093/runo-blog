<?php

namespace App\Notifications;

use App\Interfaces\INotificationChannelProvider;

class NotificationChannelProvider implements INotificationChannelProvider
{
    public function getChannels(): array
    {
        return [
            app(TwitterNotifier::class),
            app(UserNotifier::class),
            app(ModeratorNotifier::class),
        ];
    }
}
