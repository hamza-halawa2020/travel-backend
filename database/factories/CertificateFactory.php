<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CertificateFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'file' => 'certificates/' . $this->faker->slug() . '.pdf',
            'status' => $this->faker->boolean(85),
        ];
    }
}
