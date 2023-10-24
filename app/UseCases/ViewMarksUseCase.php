<?php

namespace App\UseCases;

use App\Models\MarkSheet;

class ViewMarksUseCase
{
    public function execute()
    {
        return MarkSheet::all();
    }

}
