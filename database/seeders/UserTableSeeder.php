<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


use App\Models\User;
use App\Models\Country;
use App\Models\Profile;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User;
        $user->name = 'Hardcoded User';
        $user->email = 'hardcodeduser@example.com';
        $user->email_verified_at = now();
        $user->password = bcrypt('password');
        $user->remember_token = Str::random(10);
        $user->save();


        // Create profile for hardcoded user
        $profile = new Profile;
        $profile->user_id = $user->id;
        $profile->role = 'traveller';
        $profile->bio = 'This is a hardcoded admin user profile.';
        $profile->profile_photo = null;
        $profile->social_links = json_encode(['twitter' => 'https://twitter.com/hardcodeduser']);
        $profile->country_id = Country::inRandomOrder()->first()->id;
        $profile->save();

        // Create random users and profiles using factories
        User::factory(20)->create();
    }
}
