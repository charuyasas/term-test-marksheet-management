<?php

namespace App;

enum UserRoles: string
{
    case SubjectTeacher = 'subject-teacher';
    case ClassTeacher = 'class-teacher';
    case GradeHead = 'grade-head';
    case SectionalHead = 'sectional-head';
    case Principle = 'principle';
}
