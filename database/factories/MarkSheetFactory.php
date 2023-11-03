<?php

namespace Database\Factories;

use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

class MarkSheetFactory extends Factory
{
    public function definition(): array
    {
        $students = Student::pluck('id')->toArray();
        $classes = ClassRoom::pluck('id')->toArray();
        $teachers = Teacher::pluck('id')->toArray();
        $mark = mt_rand(0 * 10, 99 * 10) / 10;
        return [
            'student_id' => $this->faker->randomElement($students),
            'class_id' => $this->faker->randomElement($classes),
            'teacher_id' => $this->faker->randomElement($teachers),
            'subject_id' => $this->faker->numberBetween(0, 10),
            'academic_year' => $this->faker->year(max: 'now'),
            'term' => $this->faker->numberBetween(1, 3),
            'marks' => $mark,
        ];
    }
}
