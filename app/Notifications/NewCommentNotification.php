<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Models\Comment;
use App\Models\User;

class NewCommentNotification extends Notification
{
    use Queueable;

    protected $comment;
    protected $commenter;

    /**
     * Create a new notification instance.
     */
    public function __construct(Comment $comment, User $commenter)
    {
        $this->comment = $comment;
        $this->commenter = $commenter;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
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
            'message' => $this->commenter->name . ' commented on your post: "' . $this->comment->post->title . '"',
            'link' => route('posts.show', $this->comment->post->id),
            'comment_id' => $this->comment->id,
        ];
    }
}
