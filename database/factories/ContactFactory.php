<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->safeEmail(),
            'age' => $this->faker->numberBetween(7, 70),
            'country' => $this->faker->country(),
            'course' => $this->faker->randomElement([
                'Noor Al-Bayan',
                'Quran Memorization',
                'Tajweed',
                'Arabic',
                'Islamic Studies',
            ]),
            'message' => $this->faker->paragraph(),
        ];
    }
}
