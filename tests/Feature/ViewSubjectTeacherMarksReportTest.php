<?php

namespace Tests\Feature;

use App\Models\ClassRoom;
use App\Models\Grade;
use App\Models\GradeClassSubjectTeacherMap;
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
    public GradeClassSubjectTeacherMap $map;
    public function setUp(): void
    {
        parent::setUp();
        $this->student = Student::factory()->create();
        $this->subject = Subject::factory()->create([
            'subject_name' => 'Maths'
        ]);
        $this->marks = MarkSheet::factory()->create([
            'student_id' => $this->student->id,
            'subject_id' => $this->subject->id,
            'marks' => 75,
        ]);
        $this->grade = Grade::factory()->create([
            'grade' => 10
        ]);
        $this->class = ClassRoom::factory()->create([
            'grade_id' => $this->grade->id,
            'class_room' => $this->grade->grade.'-A'
        ]);
        $this->teacher = Teacher::factory()->create([
            'teacher_name' => 'Upul Shantha',
            'gender' => 'male',
        ]);
        $this->map = GradeClassSubjectTeacherMap::factory()->create([
            'grade_id'=>$this->grade->id,
            'class_id'=>$this->class->id,
            'subject_id'=>$this->subject->id,
            'teacher_id'=>$this->teacher->id
        ]);
    }
    /** @test */
    public function check_unmapped_teacher(){
        $teacherB = Teacher::factory()->create([
            'teacher_name' => 'Nayana Indarani',
            'gender' => 'female',
        ]);

        $response = (new ViewMarksUseCase())->execute($teacherB,$this->class,$this->subject);

        $this->assertFalse(
            $response,
            "Given already mapped teacher"
        );
    }
}
