<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewCommentNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Comment $comment)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject("New comment from {$this->comment->author->name}")
                    ->greeting("{$this->comment->author->name} commented on the article, {$this->comment->article->title}")
                    ->line(Str::limit($this->comment->content, 50))
                    ->action('Click here to view whole article', url("/article/{$this->comment->article->id}"))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'name' => $this->comment->author->name,
            'article_id' => $this->comment->article->id,
            'comment_id' => $this->comment->id,
            'content' => $this->comment->content,
            // 'avatar_photo' => $this->article->author->avatar_photo,
        ];
    }
}
