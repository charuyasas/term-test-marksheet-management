<?php

namespace Tests\Feature;

use App\Commands\ViewStudentPerformanceCommand;
use App\Exceptions\UserRoleException;
use App\Models\ClassRoom;
use App\Models\Grade;
use App\Models\MarkSheet;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use App\Terms;
use App\UseCases\ViewStudentPerformanceUseCase;
use App\UserRoles;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ViewStudentPerformanceReportTest extends TestCase
{
    use RefreshDatabase;

    public Collection $student;
    public Collection $subject;
    public User $user;
    public Grade $grade;
    public ClassRoom $class;
    public Collection $teacher;
    public ViewStudentPerformanceCommand $command;

    public function setUp(): void
    {
        parent::setUp();

        $this->student = Student::factory()->count(50)->create();
        $this->subject = Subject::factory()->count(5)->create();
        $this->grade = Grade::factory()->create([
            'grade' => 10
        ]);
        $this->class = ClassRoom::factory()->create([
            'grade_id' => $this->grade->getKey(),
            'class_room' => $this->grade->grade . '-A'
        ]);
        $this->teacher = Teacher::factory()->count(5)->create();
        $this->generateMarkSheet();
        $this->user = User::factory()->create([
            'teacher_id' => $this->teacher[0]->getKey(),
            'name' => $this->teacher[0]->teacher_name
        ]);
        $this->command = new ViewStudentPerformanceCommand();
        $this->command->classRoom = $this->class;
        $this->command->academicYear = Carbon::parse('2021-01-01');
        $this->command->term = Terms::Third;
        Role::create(['name' => UserRoles::SubjectTeacher]);
        Role::create(['name' => UserRoles::ClassTeacher]);
        Role::create(['name' => UserRoles::GradeHead]);
        Role::create(['name' => UserRoles::SectionalHead]);
        Role::create(['name' => UserRoles::Principle]);
    }

    /** @test */
    public function when_unauthorized_user_tried_to_access_report_then_report_not_visible(): void
    {
        try {
            $this->performanceReportGenerate($this->user, $this->command);
        } catch (UserRoleException $e) {
            $this->assertEquals("No user assignment for this user", $e->getMessage());
        }
    }

    /** @test */
    public function when_authorized_user_requests_the_report_then_report_will_visible(): void
    {
        $this->user->assignRole(UserRoles::Principle);
        $marks = $this->performanceReportGenerate($this->user, $this->command);

        $this->reportViewValidation($marks, '2021', '3');
    }

    private function generateMarkSheet(): void
    {
        for ($x = 0; $x < 5; $x++) {
            foreach ($this->student as $student) {
                MarkSheet::factory()->create([
                    'subject_id' => $this->subject[$x]->getKey(),
                    'class_id' => $this->class->getKey(),
                    'teacher_id' => $this->teacher[$x]->getKey(),
                    'academic_year' => '2021',
                    'term' => '3'
                ]);
            }
        }
    }

    private function performanceReportGenerate(User $user, ViewStudentPerformanceCommand $command): Collection
    {
        $report = app(ViewStudentPerformanceUseCase::class)->execute($user, $command);

        return $report->generate();
    }

    private function reportViewValidation(Collection $marks, string $academicYear, string $term): void
    {
        foreach ($marks as $row) {
            $this->assertArrayHasKey('admission_number', $row);
            $this->assertArrayHasKey('student_name', $row);
            $this->assertArrayHasKey('gender', $row);
            $this->assertArrayHasKey('class_room', $row);
            $this->assertEquals($academicYear, $row['academic_year']);
            $this->assertEquals($term, $row['term']);
            $this->assertFalse($row['marks']->isEmpty());
        }
    }
}
