<?php

namespace App\Notifications;

use App\Models\Article;
use App\Interfaces\INotificationService;

class UserNotifier implements INotificationService
{

    public function notifyAbout(Article $article): void
    {
        echo "Check out this newly published article: " . $article->title . ", by " . $article->author->name . PHP_EOL;
    }

}
