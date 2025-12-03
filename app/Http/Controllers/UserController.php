<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function show($username)
    {
        $profile = \App\Models\Profile::where('username', $username)->firstOrFail();
        $user = $profile->user;
        $posts = $user->posts()
            ->with(['user', 'country', 'likes', 'comments'])
            ->latest()
            ->paginate(10);

        return view('users.show', compact('user', 'posts'));
    }
}