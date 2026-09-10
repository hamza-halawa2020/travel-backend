<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MediaCenterFactory extends Factory
{
    public function definition(): array
    {
        $type = $this->faker->randomElement(['image', 'video']);

        return [
            'title' => $this->faker->sentence(5),
            'type' => $type,
            'file' => $type === 'image' ? 'media/' . $this->faker->slug() . '.jpg' : null,
            'video_url' => $type === 'video' ? $this->faker->url() : null,
            'status' => $this->faker->boolean(80),
        ];
    }
}
