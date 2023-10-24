<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\UseCases\ViewMarksUseCase;
use Illuminate\Http\Request;

class SubjectTeacherMarksController extends Controller
{
    public function viewMarks(ViewMarksUseCase $marksUseCase, Subject $subject){
        return($marksUseCase->execute($subject));
    }
}
