<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;

class NewLikeNotification extends Notification
{
    use Queueable;

    protected $liker;
    protected $item;

    /**
     * Create a new notification instance.
     * FIX: Added arguments to accept the data passed from the Controller.
     */
    public function __construct(User $liker, $item)
    {
        $this->liker = $liker;
        $this->item = $item;
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
        $isPost = $this->item instanceof Post;
        $type = $isPost ? 'post' : 'comment';
        
        $postId = $isPost ? $this->item->id : $this->item->post_id;
        $link = route('posts.show', $postId);

        return [
            'message' => $this->liker->name . " liked your {$type}.",
            'link' => $link,
            'liker_id' => $this->liker->id,
            'avatar' => $this->liker->profile->profile_photo ?? null, 
        ];
    }
}