<?php

namespace App\Notifications;

use App\Interfaces\INotificationChannelProvider;

class NotificationChannelProvider implements INotificationChannelProvider
{
    public static function getChannels(): array
    {
        return [
            TwitterNotifier::class,
            UserNotifier::class,
            ModeratorNotifier::class,
        ];
    }
}
