<?php

namespace App;

enum UserRoles: string
{
    case SubjectTeacher = 'subject-teacher';
    case ClassTeacher = 'class-teacher';
}
