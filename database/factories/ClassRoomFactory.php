<?php

namespace Database\Factories;

use App\Models\Grade;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClassRoomFactory extends Factory
{
    public function definition(): array
    {
        $grade = $this->faker->randomElement(Grade::pluck('id')->toArray());
        return [
            'grade_id' => $grade,
            'class_room' => $grade.'-'.$this->faker->randomElement(['A', 'B','C','D','E'])
        ];
    }
}
