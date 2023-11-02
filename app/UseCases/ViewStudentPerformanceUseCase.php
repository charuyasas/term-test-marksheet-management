<?php

namespace App\UseCases;

use App\Commands\ViewStudentPerformanceCommand;
use App\Exceptions\UserRoleException;
use App\Exceptions\ViewStudentPerformanceException;
use App\Models\MarksGrading;
use App\Models\MarkSheet;
use App\Models\User;
use App\Reports\StudentPerformanceReport;
use App\UserRoles;

class ViewStudentPerformanceUseCase
{
    /**
     * @throws UserRoleException
     * @throws ViewStudentPerformanceException
     */
    public function execute(User $user, ViewStudentPerformanceCommand $command): StudentPerformanceReport
    {
        if (!$user->hasAnyRole([
            UserRoles::SubjectTeacher,
            UserRoles::ClassTeacher,
            UserRoles::GradeHead,
            UserRoles::SectionalHead,
            UserRoles::Principle
        ])) {
            throw UserRoleException::noUserRoleAssignment();
        }

        $markSheet = MarkSheet::query()
            ->join('students', 'students.id', '=', 'mark_sheets.student_id')
            ->join('subjects', 'subjects.id', '=', 'mark_sheets.subject_id')
            ->join('class_rooms', 'class_rooms.id', '=', 'mark_sheets.class_id')
            ->join('teachers', 'teachers.id', '=', 'mark_sheets.teacher_id')
            ->where('class_id', $command->classRoom->getKey())
            ->where('academic_year', $command->academicYear->year)
            ->where('term', $command->term)
            ->get();

        if ($markSheet->isEmpty()) {
            throw ViewStudentPerformanceException::noRelevantDataForMarkSheet();
        }

        $grading = MarksGrading::query()->get();

        return new StudentPerformanceReport($markSheet,$grading);
    }
}
