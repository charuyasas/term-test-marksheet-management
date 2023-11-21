<?php

namespace App\Commands;

use App\Models\ClassRoom;
use App\Models\Subject;
use App\Terms;
use Carbon\Carbon;

class ViewStudentPerformanceCommand
{
    public ClassRoom $classRoom;
    public Carbon $academicYear;
    public Terms $term;
}
