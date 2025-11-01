<?php

namespace Database\Seeders;



use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call(CountryTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(PostTableSeeder::class);
        $this->call(CommentTableSeeder::class);
        $this->call(LikeTableSeeder::class);
        $this->call(FollowTableSeeder::class);
    }
}
