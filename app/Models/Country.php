<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{

    use hasFactory;

    protected $fillable = ['name', 'iso_code', 'flag'];

    public function profiles()
    {
        return $this->hasMany(Profile::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
