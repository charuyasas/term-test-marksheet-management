<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class MarkSheetFactory extends Factory
{
    public function definition(): array
    {
        $students = Student::pluck('id')->toArray();
        $mark = mt_rand (0*10, 99*10) / 10;
        return [
            'student_id' => $this->faker->randomElement($students),
            'subject_id' => $this->faker->numberBetween(0, 10),
            'marks' => $mark,
        ];
    }
}
