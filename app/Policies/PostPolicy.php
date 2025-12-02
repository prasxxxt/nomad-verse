<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Post $post): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return $user->profile && in_array($user->profile->role, ['admin', 'traveller']);
    }

    /**
     * Determine whether the user can update the post.
     * Owners can update their own posts. Admins can update anything.
     */
    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id || 
               ($user->profile && $user->profile->role === 'admin');
    }

    /**
     * Determine whether the user can delete the post.
     * Owners can delete their own posts. Admins can delete anything.
     */
    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->user_id || 
               ($user->profile && $user->profile->role === 'admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Post $post): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Post $post): bool
    {
        return false;
    }
}
