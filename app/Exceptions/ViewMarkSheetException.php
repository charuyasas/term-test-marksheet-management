<?php

namespace App\Exceptions;

class ViewMarkSheetException extends \Exception
{
    public static function noSubjectAssignment(): ViewMarkSheetException
    {
        return new self('Unavailable subject assign records.');
    }
}
