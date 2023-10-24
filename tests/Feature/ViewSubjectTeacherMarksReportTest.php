<?php

namespace Tests\Feature;

use App\Models\MarkSheet;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewSubjectTeacherMarksReportTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function view_subject_marks(): void
    {
        $this->withoutExceptionHandling();

        $students = Student::factory()->create();
        $marks = MarkSheet::factory()->create();

        $this->assertDatabaseHas('students', $students->toArray());
        $this->assertDatabaseHas('mark_sheets', $marks->toArray());

        $response = $this->getJson(route('view.subject.teacher.mark.sheet'))->json();

        $this->assertEquals($marks->toArray(),$response[0]);
    }
}
