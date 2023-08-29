<?php

namespace App\Notifications;

use App\Models\Article;
use App\Interfaces\INotificationService;

class TwitterNotifier implements INotificationService
{

    public function notifyAbout(Article $article): bool
    {
        // return "Check out this cool new article! $article->title";
        return true;
    }

}
