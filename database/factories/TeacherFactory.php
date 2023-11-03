<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TeacherFactory extends Factory
{
    public function definition(): array
    {
        return [
            'teacher_name' => $this->faker->name(),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'dob' => $this->faker->date('Y-m-d', 'now'),
            'joined_date' => $this->faker->date('Y-m-d', 'now'),
            'address' => $this->faker->address(),
            'phone_number' => $this->faker->phoneNumber()
        ];
    }
}
