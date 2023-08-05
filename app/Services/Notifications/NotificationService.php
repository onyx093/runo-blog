<?php

namespace App\Services\Notifications;

use App\Models\Article;

interface NotificationService
{
    public function notifyAbout(Article $article): string;
}
