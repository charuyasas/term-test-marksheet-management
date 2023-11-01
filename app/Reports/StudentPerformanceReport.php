<?php

namespace App\Reports;

use Illuminate\Support\Collection;

class StudentPerformanceReport
{
    public function __construct(
        protected Collection $markSheet,
    )
    {
    }

    public function generate(): Collection
    {
        $students = $this->markSheet->unique('student_id');
        $studentDetails = new Collection;

        foreach ($students as $student) {
            $marks = new Collection;

            foreach ($this->markSheet->where('student_id', $student->student_id) as $mark) {
                $marks->add([
                    'subject_id' => $mark->subject_id,
                    'subject_name' => $mark->subject_name,
                    'marks' => $mark->marks,
                ]);
            }

            $studentDetails->add([
                'teacher_name' => $student->teacher_name,
                'admission_number' => $student->admission_number,
                'student_name' => $student->student_name,
                'gender' => $student->gender,
                'class_room' => $student->class_room,
                'academic_year' => $student->academic_year,
                'term' => $student->term,
                'marks' => $marks
            ]);
        }

        return $studentDetails;
    }
}
