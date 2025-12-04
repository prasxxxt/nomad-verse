<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use App\Models\User;
use App\Notifications\NewLikeNotification;

class LikeController extends Controller
{
    public function toggle($type, $id)
    {
        $model = null;
        if ($type === 'post') {
            $model = Post::findOrFail($id);
        } elseif ($type === 'comment') {
            $model = Comment::findOrFail($id);
        } else {
            abort(404);
        }

        $user = auth()->user();
        
        $existingLike = $model->likes()->where('user_id', $user->id)->first();

        $liked = false;
        if ($existingLike) {
            $existingLike->delete();
            $liked = false;
        } else {
            $model->likes()->create(['user_id' => $user->id]);
            $liked = true;

            if ($model->user_id !== $user->id) {
                $model->user->notify(new NewLikeNotification($user, $model));
            }
        }

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'count' => $model->likes()->count(),
        ]);
    }
}