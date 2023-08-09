<?php

namespace App\Notifications;

use App\Models\Article;
use App\Interfaces\INotificationService;

class ModeratorNotifier implements INotificationService
{

    public function notifyAbout(Article $article): bool
    {
        // return "A new article, $article->title, has just been published, and it is available for review";
        return true;
    }

}
