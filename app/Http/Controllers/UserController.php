<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display the specified user's public profile.
     */
    public function show(User $user)
    {
        // Ensure the profile relation is loaded
        $user->load('profile');

        $posts = $user->posts()
            ->with(['user', 'country', 'likes', 'comments'])
            ->latest()
            ->paginate(10);

        return view('users.show', compact('user', 'posts'));
    }
}