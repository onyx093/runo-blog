<?php

namespace App\Interfaces;

use App\Models\Article;

interface INotificationService
{
    public function notifyAbout(Article $article): bool;
}
