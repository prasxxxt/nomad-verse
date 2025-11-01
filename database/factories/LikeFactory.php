<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Like>
 */
class LikeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $userId = User::inRandomOrder()->first()->id;

        $likeableType = $this->faker->randomElement([
            Post::class,
            Comment::class,
        ]);

        if ($likeableType === Post::class) {
            $likeableId = Post::inRandomOrder()->first()->id;
        } else {
            $likeableId = Comment::inRandomOrder()->first()->id;
        }

        return [
            'user_id' => $userId,
            'likeable_id' => $likeableId,
            'likeable_type' => $likeableType,
        ];
    }
}
