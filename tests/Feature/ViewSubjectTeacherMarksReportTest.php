<?php

namespace Tests\Feature;

use App\Commands\ViewMarksCommand;
use App\Exceptions\ViewMarkSheetException;
use App\Http\Requests\ViewMarksRequest;
use App\Models\ClassRoom;
use App\Models\Grade;
use App\Models\SubjectAssignmentSchedule;
use App\Models\MarkSheet;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\UseCases\ViewMarksUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewSubjectTeacherMarksReportTest extends TestCase
{
    use RefreshDatabase;

    public Student $student;
    public Subject $subject;
    public MarkSheet $marks;
    public Grade $grade;
    public ClassRoom $class;
    public Teacher $teacher;
    public SubjectAssignmentSchedule $schedule;
    public ViewMarksRequest $viewMarksRequest;
    public ViewMarksCommand $command;

    public function setUp(): void
    {
        parent::setUp();
        Student::factory()->count(10)->create();
        $this->subject = Subject::factory()->create([
            'subject_name' => 'Maths'
        ]);
        $this->grade = Grade::factory()->create([
            'grade' => 10
        ]);
        $this->class = ClassRoom::factory()->create([
            'grade_id' => $this->grade->id,
            'class_room' => $this->grade->grade . '-A'
        ]);
        MarkSheet::factory()->count(10)->create([
            'subject_id' => $this->subject->id,
            'class_id' => $this->class->id
        ]);
        $this->teacher = Teacher::factory()->create([
            'teacher_name' => 'Upul Shantha',
            'gender' => 'male',
        ]);
        $this->schedule = SubjectAssignmentSchedule::factory()->create([
            'grade_id' => $this->grade->id,
            'class_id' => $this->class->id,
            'subject_id' => $this->subject->id,
            'teacher_id' => $this->teacher->id
        ]);

        $this->viewMarksRequest = new ViewMarksRequest();
        $this->command = $this->viewMarksRequest->command($this->subject, $this->class);
    }

    /** @test */
    public function when_unassigned_teacher_request_mark_sheet_then_cannot_access_mark_sheet()
    {
        $teacherB = Teacher::factory()->create([
            'teacher_name' => 'Tania Fernando',
            'gender' => 'female',
        ]);

        try {
            (new ViewMarksUseCase())->execute($teacherB, $this->command);
        } catch (ViewMarkSheetException $e) {
            $this->assertEquals("Unavailable subject assign records.", $e->getMessage());
        }
    }

    /** @test
     * @throws ViewMarkSheetException
     */
    public function when_assigned_teacher_requests_mark_sheet_then_mark_sheet_return()
    {
        $report = (new ViewMarksUseCase())->execute($this->teacher, $this->command);

        $this->assertNotEmpty($report);

        foreach ($report as $row) {
            $this->assertArrayHasKey('teacher_name', $row);
            $this->assertArrayHasKey('student_name', $row);
            $this->assertArrayHasKey('subject_name', $row);
            $this->assertArrayHasKey('class_room', $row);
            $this->assertArrayHasKey('marks', $row);
        }
    }
}
