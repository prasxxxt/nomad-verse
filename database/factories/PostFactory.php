<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\PostMedia;
use App\Models\Profile;
use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $travellerUserIds = Profile::whereIn('role', ['traveller', 'admin'])
            ->pluck('user_id')
            ->toArray();

        if (empty($travellerUserIds)) {
            $travellerUserIds = [\App\Models\User::factory()->create()->id];
        }

        return [
            'user_id' => $this->faker->randomElement($travellerUserIds),
            'country_id' => Country::inRandomOrder()->first()->id ?? Country::factory(),
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
        ];
    }

    /**
     * Configure the factory to add media after creating the post.
     */
    public function configure()
    {
        return $this->afterCreating(function (Post $post) {
            if ($this->faker->boolean(70)) {
                $count = rand(1, 5);
                
                for ($i = 0; $i < $count; $i++) {
                    $type = $this->faker->randomElement(['image', 'image', 'image', 'video']);
                    
                    PostMedia::create([
                        'post_id' => $post->id,
                        'file_type' => $type,
                        'file_path' => $type === 'video' 
                            ? 'https://www.w3schools.com/html/mov_bbb.mp4'
                            : 'https://placehold.net/800x600.png',
                        'position' => $i,
                    ]);
                }
            }
        });
    }
}