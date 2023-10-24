<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('subjects/{subject}/marks', [\App\Http\Controllers\SubjectTeacherMarksController::class, 'viewMarks'])->name('view.subject.teacher.mark.sheet');
