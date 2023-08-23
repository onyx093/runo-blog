<?php

namespace App\Listeners;

use App\Events\CommentCreated;
use App\Notifications\NewCommentNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendCommentCreatedNotifications
{
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
    public function handle(CommentCreated $event): void
    {
        $author = $event->comment->article->author;
        $author->notify(new NewCommentNotification($event->comment));

        // These are the authors of the comments on the article
        $article_comments = $event->comment->article->comments;
        $all_comment_users = [];
        foreach ($article_comments as $article_comment) {
            $article_comment->load('author');
            if(in_array($article_comment->author, $all_comment_users)){
                continue;
            }
            if($article_comment->author->id === $event->comment->author_id){
                continue;
            }
            $all_comment_users[] = $article_comment->author;
        }
        Notification::send($all_comment_users, new NewCommentNotification($event->comment));
    }
}
