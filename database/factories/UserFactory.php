<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);

    }

    public function configure()
    {
        return $this->afterCreating(function (\App\Models\User $user) {
            $user->profile()->create([
                'role' => $this->faker->randomElement(['admin', 'traveller', 'viewer']),
                'username' => $this->faker->unique()->userName(),
                'bio' => $this->faker->paragraph(),
                'profile_photo' => 'https://placehold.net/600x600.png',
                'social_links' => json_encode([
                    'twitter' => $this->faker->url,
                    'instagram' => $this->faker->url,
                ]),
                'country_id' => \App\Models\Country::inRandomOrder()->first()->id,
            ]);
        });
    }
}
