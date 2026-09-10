<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraphs(2, true),
            'image' => 'courses/' . $this->faker->slug() . '.jpg',
            'status' => $this->faker->boolean(80),
        ];
    }
}
