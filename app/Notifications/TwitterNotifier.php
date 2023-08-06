<?php

namespace App\Notifications;

use App\Models\Article;
use App\Interfaces\INotificationService;

class TwitterNotifier implements INotificationService
{

    public function notifyAbout(Article $article): void
    {
        echo "Check out this cool new article! $article->title" . PHP_EOL;
    }

}
