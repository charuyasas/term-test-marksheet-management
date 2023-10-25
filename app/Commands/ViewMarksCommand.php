<?php

namespace App\Commands;

use App\Models\ClassRoom;
use App\Models\Subject;

class ViewMarksCommand
{
    public ClassRoom $classRoom;
    public Subject $subject;
}
