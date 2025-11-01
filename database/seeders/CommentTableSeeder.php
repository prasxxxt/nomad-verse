<?php

namespace Database\Seeders;


use App\Models\User;
use App\Models\Post;
use App\Models\Comment;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $comment = new Comment;
        $comment->user_id = User::inRandomOrder()->first()->id;
        $comment->post_id = Post::inRandomOrder()->first()->id;
        $comment->content = 'This is a hardcoded comment example.';
        $comment->save();


        Comment::factory(100)->create();
    }
}
