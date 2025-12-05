<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Display the public profile edit form.
     */
    public function editPublic(Request $request): View
    {
        $countries = \App\Models\Country::orderBy('name')->get();
        
        return view('profile.edit_public', [
            'user' => $request->user(),
            'countries' => $countries,
        ]);
    }

    public function updatePublic(Request $request): RedirectResponse
    {
        $user = $request->user();
        $profile = $user->profile;

        // 1. Validate
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:profiles,username,' . $profile->id],
            'bio' => ['nullable', 'string', 'max:1000'],
            'country_id' => ['nullable', 'exists:countries,id'],
            'twitter' => ['nullable', 'url', 'max:255'],
            'instagram' => ['nullable', 'url', 'max:255'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
            'remove_photo' => ['nullable', 'boolean'],
            'become_traveller' => ['nullable', 'boolean'], // <--- NEW VALIDATION
        ]);

        // 2. Update User Account
        $user->fill(['name' => $validated['name'], 'email' => $validated['email']]);
        if ($user->isDirty('email')) $user->email_verified_at = null;
        $user->save();

        // 3. Handle Role Upgrade (Irreversible)
        if ($request->boolean('become_traveller') && $profile->role === 'viewer') {
            $profile->role = 'traveller';
        }

        // 4. Handle Photo
        if ($request->boolean('remove_photo') && $profile->profile_photo) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete(str_replace('storage/', '', $profile->profile_photo));
            $profile->profile_photo = null;
        }
        if ($request->hasFile('profile_photo')) {
            if ($profile->profile_photo) \Illuminate\Support\Facades\Storage::disk('public')->delete(str_replace('storage/', '', $profile->profile_photo));
            $profile->profile_photo = 'storage/' . $request->file('profile_photo')->store('profiles', 'public');
        }

        // 5. Save Profile
        $socials = json_decode($profile->social_links, true) ?? [];
        $socials['twitter'] = $validated['twitter'];
        $socials['instagram'] = $validated['instagram'];
        
        $profile->username = $validated['username'];
        $profile->bio = $validated['bio'];
        $profile->country_id = $validated['country_id'];
        $profile->social_links = json_encode($socials);
        $profile->save();

        return redirect()->route('users.show', $profile->username)->with('status', 'Profile updated!');
    }
}
