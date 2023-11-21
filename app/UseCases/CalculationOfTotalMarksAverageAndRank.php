<?php

namespace App\UseCases;

use App\Exceptions\UserRoleException;
use App\Models\User;
use App\UserRoles;

class CalculationOfTotalMarksAverageAndRank
{
    public function execute(User $user){
        if (!$user->hasAnyRole([
            UserRoles::SubjectTeacher,
            UserRoles::ClassTeacher,
            UserRoles::GradeHead,
            UserRoles::SectionalHead,
            UserRoles::Principle
        ])) {
            throw UserRoleException::noUserRoleAssignment();
        }

    }
}
