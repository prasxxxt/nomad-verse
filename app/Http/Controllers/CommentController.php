<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Notifications\NewCommentNotification;

class CommentController extends Controller
{
    public function store(Request $request, $postId)
    {
        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        $post = Post::findOrFail($postId);

        $comment = $post->comments()->create([
            'content' => $request->input('content'),
            'user_id' => auth()->id(),
        ]);

        if ($post->user_id !== auth()->id()) {
            $post->user->notify(new NewCommentNotification($comment, auth()->user()));
        }

        return response()->json([
            'success' => true,
            'comment' => $comment,
            'user_name' => auth()->user()->name,
            'created_at' => $comment->created_at->diffForHumans(),
        ]);
    }
}