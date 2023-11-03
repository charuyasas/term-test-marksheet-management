<?php

namespace App\Exceptions;

class ViewMarkSheetException extends \Exception
{
    public static function noSubjectAssignment(): ViewMarkSheetException
    {
        return new self('Unavailable subject assign records.');
    }

    public static function noAcademicYear(): ViewMarkSheetException
    {
        return new self('Valid Academic Year required.');
    }

    public static function noTerm(): ViewMarkSheetException
    {
        return new self('Valid term number required.');
    }

    public static function noUserRoleAssignment(): ViewMarkSheetException
    {
        return new self('No user assignment for this user');
    }
}
