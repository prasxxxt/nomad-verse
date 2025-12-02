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

        $user1 = User::find(1);
        $user2 = User::find(2);
        $user3 = User::find(3);

        // Manually insert follow relationships
        // DB::table('follows')->insert([
        //     [
        //         'user_id' => $user1->id,
        //         'followed_user_id' => $user2->id,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'user_id' => $user1->id,
        //         'followed_user_id' => $user3->id,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ],
        //     [
        //         'user_id' => $user2->id,
        //         'followed_user_id' => $user3->id,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]
        // ]);

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
