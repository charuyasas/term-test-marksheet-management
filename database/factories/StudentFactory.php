<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\odel>
 */
class StudentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'student_name' => $this->faker->name(),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'dob' => $this->faker->date('Y-m-d', 'now'),
            'enlisted_date' => $this->faker->date('Y-m-d', 'now'),
        ];
    }
}
