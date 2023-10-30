<?php

namespace App\Commands;

use App\Exceptions\ViewMarkSheetException;
use App\Models\ClassRoom;
use App\Models\Subject;
use App\Terms;
use Carbon\Carbon;

class ViewMarksCommand
{
    public ClassRoom $classRoom;
    public Subject $subject;
    public Carbon $academicYear;
    public Terms $term;
}
