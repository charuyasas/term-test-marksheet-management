<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewSubjectTeacherMarksReportTest extends TestCase
{
    /** @test */
    public function view_subject_marks(): void
    {
        $this->withoutExceptionHandling();
        $response = $this->getJson(route('view.subject.teacher.mark.sheet'));

        $response->assertStatus(200);
    }
}
