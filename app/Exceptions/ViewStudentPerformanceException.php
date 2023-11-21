<?php

namespace App\Exceptions;

class ViewStudentPerformanceException extends \Exception
{
    public static function noRelevantDataForMarkSheet(): ViewStudentPerformanceException
    {
        return new self('No relevant mark sheet data available');
    }
}
