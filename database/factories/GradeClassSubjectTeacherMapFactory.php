<?php

namespace Database\Factories;

use App\Models\ClassRoom;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

class GradeClassSubjectTeacherMapFactory extends Factory
{
    public function definition(): array
    {
        $grade = $this->faker->randomElement(Grade::pluck('id')->toArray());
        $classRoom = $this->faker->randomElement(ClassRoom::pluck('id')->toArray());
        $subject = $this->faker->randomElement(Subject::pluck('id')->toArray());
        $teacher = $this->faker->randomElement(Teacher::pluck('id')->toArray());
        return [
            'grade_id' => $grade,
            'class_id' => $classRoom,
            'subject_id' => $subject,
            'teacher_id' => $teacher
        ];
    }
}
