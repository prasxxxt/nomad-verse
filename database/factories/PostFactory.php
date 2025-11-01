<?php

namespace Database\Factories;

use App\Models\Profile;
use App\Models\Post;
use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $travellerUserIds = Profile::where('role', 'traveller')->pluck('user_id')->toArray();

        return [
            'user_id' => $this->faker->randomElement($travellerUserIds),
            'country_id' => Country::inRandomOrder()->first()->id,
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'image' => $this->faker->imageUrl(640, 480, 'travel'),
        ];
    }
}
