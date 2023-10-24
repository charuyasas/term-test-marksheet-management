<?php

namespace App\UseCases;

use App\Models\ClassRoom;
use App\Models\GradeClassSubjectTeacherMap;
use App\Models\MarkSheet;
use App\Models\Subject;
use App\Models\Teacher;

class ViewMarksUseCase
{
    public function execute(Teacher $teacher, ClassRoom $classRoom, Subject $subject)
    {
        $mappingAvailable = GradeClassSubjectTeacherMap::where('grade_id', $classRoom->grade_id)->where('class_id', $classRoom->id)->where('subject_id', $subject->id)->where('teacher_id', $teacher->id)->get();

        if ($mappingAvailable->count() == 0) {
            return false;
        }
    }
}
