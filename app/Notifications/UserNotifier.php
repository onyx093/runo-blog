<?php

namespace App\Notifications;

use App\Models\Article;
use App\Interfaces\INotificationService;

class UserNotifier implements INotificationService
{

    public function notifyAbout(Article $article): bool
    {
        // return "Check out this newly published article: " . $article->title . ", by " . $article->author->name;
        return true;
    }

}
