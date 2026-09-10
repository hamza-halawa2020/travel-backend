<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MainSliderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(5),
            'description' => $this->faker->sentence(12),
            'link' => '/' . $this->faker->slug(),
            'image' => 'sliders/' . $this->faker->slug() . '.jpg',
            'status' => $this->faker->boolean(80),
        ];
    }
}
