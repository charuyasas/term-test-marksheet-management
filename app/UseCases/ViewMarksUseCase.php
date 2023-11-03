<?php

namespace App\UseCases;

use App\Commands\ViewMarksCommand;
use App\Exceptions\ViewMarkSheetException;
use App\Models\MarkSheet;
use App\Models\SubjectAssignmentSchedule;
use App\Models\User;
use App\Reports\MarksReport;
use App\UserRoles;
use Illuminate\Support\Collection;

class ViewMarksUseCase
{
    /**
     * @throws ViewMarkSheetException
     */
    public function execute(User $user, ViewMarksCommand $command): MarksReport
    {
        if (! $user->hasAnyRole([UserRoles::SubjectTeacher,UserRoles::ClassTeacher])) {
            throw ViewMarkSheetException::noUserRoleAssignment();
        }

        if ($user->hasRole(UserRoles::SubjectTeacher)) {
            $subjectAssignment = SubjectAssignmentSchedule::query()
                ->where('grade_id', $command->classRoom->grade_id)
                ->where('class_id', $command->classRoom->getKey())
                ->where('subject_id', $command->subject->getKey())
                ->where('teacher_id', $user->getKey())
                ->where('academic_year', $command->academicYear->year)
                ->get();

            if ($subjectAssignment->isEmpty()) {
                throw ViewMarkSheetException::noSubjectAssignment();
            }
        }

        /** @var Collection $markSheet */
        $markSheet = MarkSheet::query()
            ->join('students', 'students.id', '=', 'mark_sheets.student_id')
            ->join('subjects', 'subjects.id', '=', 'mark_sheets.subject_id')
            ->join('class_rooms', 'class_rooms.id', '=', 'mark_sheets.class_id')
            ->join('teachers', 'teachers.id', '=', 'mark_sheets.teacher_id')
            ->where('subject_id', $command->subject->getKey())
            ->where('class_id', $command->classRoom->getKey())
            ->where('academic_year', $command->academicYear->year)
            ->where('term', $command->term)
            ->get();

        return new MarksReport($user, $markSheet);
    }
}
