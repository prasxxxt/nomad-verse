<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\TravelMapService;

class UserController extends Controller
{
    /**
     * Display the specified user's public profile.
     */
    public function show($username, TravelMapService $mapService)
    {
        $profile = \App\Models\Profile::where('username', $username)->firstOrFail();
        $user = $profile->user;

        $posts = $user->posts()
            ->with(['user', 'country', 'likes', 'comments', 'media'])
            ->latest()
            ->paginate(10);

        $visitedCountries = $mapService->getVisitedCountryCodes($user);

        return view('users.show', compact('user', 'posts', 'visitedCountries'));
    }
}