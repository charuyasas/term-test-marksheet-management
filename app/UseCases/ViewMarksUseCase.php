<?php

namespace App\UseCases;

use App\Commands\ViewMarksCommand;
use App\Exceptions\ViewMarkSheetException;
use App\Models\MarkSheet;
use App\Models\SubjectAssignmentSchedule;
use App\Models\Teacher;
use App\Reports\MarksReport;
use Illuminate\Support\Collection;

class ViewMarksUseCase
{
    /**
     * @throws ViewMarkSheetException
     */
    public function execute(Teacher $teacher, ViewMarksCommand $command): MarksReport
    {
        $subjectAssignment = SubjectAssignmentSchedule::query()
            ->where('grade_id', $command->classRoom->grade_id)
            ->where('class_id', $command->classRoom->getKey())
            ->where('subject_id', $command->subject->getKey())
            ->where('teacher_id', $teacher->getKey())
            ->get();

        if ($subjectAssignment->isEmpty()) throw ViewMarkSheetException::noSubjectAssignment();

        /** @var Collection $markSheet */
        $markSheet = MarkSheet::query()
            ->join('students', 'students.id', '=', 'mark_sheets.student_id')
            ->join('subjects', 'subjects.id', '=', 'mark_sheets.subject_id')
            ->join('class_rooms', 'class_rooms.id', '=', 'mark_sheets.class_id')
            ->where('subject_id', $command->subject->getKey())
            ->where('class_id', $command->classRoom->getKey())
            ->get();

        return new MarksReport($teacher, $markSheet);
    }
}
