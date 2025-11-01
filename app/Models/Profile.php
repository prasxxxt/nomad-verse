<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = ['user_id', 'role', 'bio', 'profile_photo', 'social_links'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
