<?php

namespace Tests\Feature;

use App\Commands\ViewMarksCommand;
use App\Exceptions\ViewMarkSheetException;
use App\Models\ClassRoom;
use App\Models\Grade;
use App\Models\SubjectAssignmentSchedule;
use App\Models\MarkSheet;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use App\Terms;
use App\UseCases\ViewMarksUseCase;
use App\UserRoles;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ViewSubjectTeacherMarksReportTest extends TestCase
{
    use RefreshDatabase;

    public Student $student;
    public Subject $subject;
    public User $user;
    public Grade $grade;
    public ClassRoom $class;
    public Teacher $teacher;
    public SubjectAssignmentSchedule $schedule;
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
            'grade_id' => $this->grade->getKey(),
            'class_room' => $this->grade->grade . '-A'
        ]);
        $this->teacher = Teacher::factory()->create([
            'teacher_name' => 'Upul Shantha',
            'gender' => 'male',
        ]);
        MarkSheet::factory()->count(10)->create([
            'subject_id' => $this->subject->getKey(),
            'class_id' => $this->class->getKey(),
            'teacher_id' => $this->teacher->getKey(),
            'academic_year' => '2021',
            'term' => '3'
        ]);
        $this->user = User::factory()->create([
            'teacher_id' => $this->teacher->getKey(),
            'name' => $this->teacher->teacher_name
        ]);
        $this->subjectAssigningToTeacher('2021');
        $this->command = new ViewMarksCommand();
        $this->command->subject = $this->subject;
        $this->command->classRoom = $this->class;
        Role::create(['name' => UserRoles::SubjectTeacher]);
        Role::create(['name' => UserRoles::ClassTeacher]);
    }

    /** @test */
    public function when_subject_unassigned_subject_teacher_request_mark_sheet_then_cannot_access_mark_sheet()
    {
        $this->command->academicYear = Carbon::parse('2021-01-01');
        $this->command->term = Terms::Third;
        $teacherB = Teacher::factory()->create([
            'teacher_name' => 'Tania Fernando',
            'gender' => 'female',
        ]);
        $userB = User::factory()->create([
            'teacher_id' => $teacherB->getKey(),
            'name' => $teacherB->teacher_name
        ]);
        $userB->assignRole(UserRoles::SubjectTeacher);

        try {
            $this->marksReportGenerate($userB, $this->command);
        } catch (ViewMarkSheetException $e) {
            $this->assertEquals("Unavailable subject assign records.", $e->getMessage());
        }
    }

    /** @test */
    public function when_subject_assigned_teacher_requests_mark_sheet_then_view_mark_sheet()
    {
        $this->user->assignRole(UserRoles::SubjectTeacher);
        $this->command->academicYear = Carbon::parse('2021-01-01');
        $this->command->term = Terms::Third;

        $marks = $this->marksReportGenerate($this->user, $this->command);

        $this->assertNotEmpty($marks);
        $this->reportViewValidation($marks, '2021', 3);
    }

    /** @test */
    public function when_students_passing_there_grade_then_mark_sheet_generate_depend_on_the_academic_year_and_term()
    {
        $this->user->assignRole(UserRoles::SubjectTeacher);
        $this->command->academicYear = Carbon::parse('2022-01-01');
        $this->command->term = Terms::First;
        $this->subjectAssigningToTeacher('2022');
        $students = Student::factory()->count(10)->create()->toArray();
        foreach ($students as $student) {
            MarkSheet::factory()->create([
                'student_id' => $student['id'],
                'subject_id' => $this->subject->getKey(),
                'class_id' => $this->class->getKey(),
                'academic_year' => '2022',
                'term' => '1'
            ]);
        }

        $marks = $this->marksReportGenerate($this->user, $this->command);

        $this->assertNotEmpty($marks);
        $this->reportViewValidation($marks, '2022', 1);
    }

    /** @test */
    public function when_authorized_logged_user_request_marks_sheet_then_view_mark_sheet()
    {
        $this->user->assignRole(UserRoles::ClassTeacher);
        $this->command->academicYear = Carbon::parse('2021-01-01');
        $this->command->term = Terms::Third;

        $marks = $this->marksReportGenerate($this->user, $this->command);

        $this->assertNotEmpty($marks);
        $this->reportViewValidation($marks, '2021', 3);
    }

    private function marksReportGenerate(User $user, ViewMarksCommand $command): Collection
    {
        $report = app(ViewMarksUseCase::class)->execute($user, $command);

        return $report->generate();
    }

    private function reportViewValidation(Collection $marks, string $academicYear, string $term): void
    {
        foreach ($marks as $row) {
            $this->assertArrayHasKey('teacher_name', $row);
            $this->assertArrayHasKey('admission_number', $row);
            $this->assertArrayHasKey('student_name', $row);
            $this->assertArrayHasKey('gender', $row);
            $this->assertArrayHasKey('subject_name', $row);
            $this->assertArrayHasKey('class_room', $row);
            $this->assertArrayHasKey('marks', $row);
            $this->assertEquals($academicYear, $row['academic_year']);
            $this->assertEquals($term, $row['term']);
        }
    }

    private function subjectAssigningToTeacher(string $academicYear): void
    {
        SubjectAssignmentSchedule::factory()->create([
            'grade_id' => $this->grade->getKey(),
            'class_id' => $this->class->getKey(),
            'subject_id' => $this->subject->getKey(),
            'teacher_id' => $this->teacher->getKey(),
            'academic_year' => $academicYear
        ]);
    }
}
