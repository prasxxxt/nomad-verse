<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        protected $model = Profile::class;

        return [
            // Will be set externally when called from UserFactory
            'role' => $this->faker->randomElement(['admin', 'traveller', 'viewer']),
            'bio' => $this->faker->paragraph(),
            'profile_photo' => $this->faker->imageUrl(200, 200, 'people'),
            'social_links' => json_encode([
                'twitter' => $this->faker->url,
                'instagram' => $this->faker->url,
            ]),
            'country_id' => Country::inRandomOrder()->first()->id,
        ];
    }
}
