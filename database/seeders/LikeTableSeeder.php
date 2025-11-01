<?php

namespace Database\Seeders;

use App\Models\Like;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;




use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LikeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $user = User::inRandomOrder()->first();
        $post = Post::inRandomOrder()->first();

        $like = new Like;
        $like->user_id = $user->id;
        $like->likeable_id = $post->id;
        $like->likeable_type = get_class($post);
        $like->save();

        Like::factory()
            ->count(50)
            ->make()
            ->each(function ($like) {
                Like::firstOrCreate([
                    'user_id' => $like->user_id,
                    'likeable_id' => $like->likeable_id,
                    'likeable_type' => $like->likeable_type,
                ]);
            });
    }
}
