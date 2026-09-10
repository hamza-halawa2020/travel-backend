<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(6),
            'description' => $this->faker->paragraphs(3, true),
            'image' => 'posts/' . $this->faker->slug() . '.jpg',
            'status' => $this->faker->boolean(75),
        ];
    }
}
