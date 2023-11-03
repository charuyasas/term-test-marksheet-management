<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SubjectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'subject_name' => $this->faker->randomElement(['Maths', 'science', 'history', 'music', 'IT']),
        ];
    }
}
