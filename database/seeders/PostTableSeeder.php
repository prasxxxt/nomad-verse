<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Profile;
use App\Models\Country;
use App\Models\Post;


class PostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $post = new Post;
        $post->user_id = Profile::whereIn('role', ['traveller', 'admin'])->first()->user->id;
        $post->country_id = Country::inRandomOrder()->first()->id;
        $post->title = 'Hardcoded Travel Post';
        $post->description = 'A hardcoded example: only admin and traveller users can post!';
        $post->image = "https://image.com/600x400.png";
        $post->save();

        
        Post::factory(5)->create();    
    }
}
