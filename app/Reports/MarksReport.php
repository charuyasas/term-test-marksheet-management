<?php

namespace App\Reports;

use App\Models\Teacher;
use Illuminate\Support\Collection;

class MarksReport
{
    public function __construct(
        protected Teacher    $teacher,
        protected Collection $markSheet,
    )
    {
    }

    public function generateReport(): array
    {
        $marks = array();
        foreach ($this->markSheet as $mark) {
            $marks[] = [
                'teacher_name' => $this->teacher->teacher_name,
                'student_name' => $mark->student_name,
                'subject_name' => $mark->subject_name,
                'class_room' => $mark->class_room,
                'marks' => $mark->marks,
            ];
        }
        return $marks;
    }
}
