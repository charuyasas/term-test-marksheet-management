<?php

namespace App\Http\Requests;

use App\Commands\ViewMarksCommand;
use App\Models\ClassRoom;
use App\Models\Subject;
use Illuminate\Foundation\Http\FormRequest;

class ViewMarksRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }

    public function command(Subject $subject, ClassRoom $classRoom):ViewMarksCommand
    {

    }
}
