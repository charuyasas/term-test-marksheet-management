<?php

namespace App\Reports;

use App\Models\User;
use Illuminate\Support\Collection;

class MarksReport
{
    public function __construct(
        protected User       $user,
        protected Collection $markSheet,
    )
    {
    }

    public function generate(): Collection
    {
        $marks = new Collection;

        foreach ($this->markSheet as $mark) {
            $marks->add([
                'teacher_name' => $mark->teacher_name,
                'admission_number' => $mark->admission_number,
                'student_name' => $mark->student_name,
                'gender' => $mark->gender,
                'subject_name' => $mark->subject_name,
                'class_room' => $mark->class_room,
                'marks' => $mark->marks,
                'academic_year' => $mark->academic_year,
                'term' => $mark->term,
            ]);
        }

        return $marks;
    }
}
