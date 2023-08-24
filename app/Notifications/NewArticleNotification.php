<?php

namespace App\Notifications;

use App\Models\Article;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;

class NewArticleNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Article $article)
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
            ->subject("New Article from {$this->article->author->name}")
            ->greeting("A new article from {$this->article->author->name}")
            ->line(Str::limit($this->article->content, 50))
            ->action('Check out the article here', url("/article/{$this->article->id}"))
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
            'type' => 'article',
            'article_id' => $this->article->id,
            'title' => $this->article->title,
            'content' => $this->article->author->name . " published a new article!",
            'user_avatar' => $this->article->author->avatar_url,
        ];
    }
}
