<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FollowTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $count = 100;
        
        $users = User::pluck('id')->toArray();
        $totalUsers = count($users);

        $follows = [];

        while (count($follows) < $count) {
            $follower = $users[array_rand($users)];
            $followed = $users[array_rand($users)];

            // Skip self-follows
            if ($follower === $followed) continue;

            $key = $follower . '-' . $followed;

            // Prevent duplicates
            if (isset($follows[$key])) continue;

            $follows[$key] = [
                'user_id' => $follower,
                'followed_user_id' => $followed,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert all at once
        DB::table('follows')->insert(array_values($follows));
    }
    
}
