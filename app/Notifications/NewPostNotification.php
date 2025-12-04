<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Post;
use App\Models\User;

class NewPostNotification extends Notification
{
    use Queueable;

    protected $post;
    protected $author;

    /**
     * Create a new notification instance.
     * FIX: Added arguments to accept the Post and Author data.
     */
    public function __construct(Post $post, User $author)
    {
        $this->post = $post;
        $this->author = $author;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => $this->author->name . ' shared a new memory: "' . \Illuminate\Support\Str::limit($this->post->title, 20) . '"',
            'link' => route('posts.show', $this->post->id),
            'avatar' => $this->author->profile->profile_photo ?? null,
        ];
    }
}