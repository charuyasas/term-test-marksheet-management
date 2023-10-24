<?php

namespace App\Http\Controllers;

use App\UseCases\ViewMarksUseCase;
use Illuminate\Http\Request;

class SubjectTeacherMarksController extends Controller
{
    public function viewMarks(ViewMarksUseCase $marksUseCase){
        return($marksUseCase->execute());
    }
}
