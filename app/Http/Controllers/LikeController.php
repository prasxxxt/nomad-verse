<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;

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
            $existingLike->delete(); // Unlike
            $liked = false;
        } else {
            $model->likes()->create(['user_id' => $user->id]); // Like
            $liked = true;
        }

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'count' => $model->likes()->count(),
        ]);
    }
}