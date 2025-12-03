<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\NewFollowerNotification;

class FollowController extends Controller
{
    public function toggle(User $user)
    {
        $currentUser = auth()->user();

        if ($currentUser->id === $user->id) {
            if (request()->wantsJson()) {
                return response()->json(['error' => 'Cannot follow self'], 422);
            }
            return back()->with('error', 'You cannot follow yourself.');
        }

        $currentUser->follows()->toggle($user->id);

        $isFollowing = $currentUser->follows()->where('followed_user_id', $user->id)->exists();

        if ($isFollowing) {
            $user->notify(new NewFollowerNotification($currentUser));
        }
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'following' => $isFollowing,
                'count' => $user->followers()->count()
            ]);
        }
        return back()->with('message', $isFollowing ? 'You are now following ' . $user->name : 'Unfollowed ' . $user->name);
    }
}