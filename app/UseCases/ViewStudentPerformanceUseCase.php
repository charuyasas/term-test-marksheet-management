<?php

namespace App\UseCases;

use App\Exceptions\UserRoleException;
use App\Models\Teacher;
use App\Models\User;
use App\UserRoles;

class ViewStudentPerformanceUseCase
{
    public function execute(User $user){
        $teacher = Teacher::factory()->create([
            'teacher_name' => 'Upul Shantha',
            'gender' => 'male',
        ]);

        $user = User::factory()->create([
            'teacher_id' => $teacher->getKey(),
            'name' => $teacher->teacher_name
        ]);

        if (!$user->hasAnyRole([UserRoles::SubjectTeacher, UserRoles::ClassTeacher])) {
            throw UserRoleException::noUserRoleAssignment();
        }
    }
}
