<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Notifications\NewCommentNotification;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    public function store(Request $request, $postId)
    {
=        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        $post = Post::findOrFail($postId);

        $comment = $post->comments()->create([
            'content' => $request->input('content'),
            'user_id' => auth()->id(),
        ]);

        if ($post->user_id !== auth()->id()) {
            try {
                $post->user->notify(new NewCommentNotification($comment, auth()->user()));
            } catch (\Exception $e) {
                Log::error("Comment Notification Failed: " . $e->getMessage());
            }
        }

        $avatarUrl = null;
        if (auth()->user()->profile && auth()->user()->profile->profile_photo) {
            $avatarUrl = asset(auth()->user()->profile->profile_photo);
        }

=        return response()->json([
            'success' => true,
            'comment' => $comment,
            'user_name' => auth()->user()->name,
            'created_at' => 'Just now',
            'user_avatar' => $avatarUrl,
        ]);
    }
}