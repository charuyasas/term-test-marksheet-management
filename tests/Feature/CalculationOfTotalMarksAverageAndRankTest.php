<?php

namespace Tests\Feature;

use App\Commands\ViewStudentPerformanceCommand;
use App\Exceptions\UserRoleException;
use App\Models\ClassRoom;
use App\Models\Grade;
use App\Models\Teacher;
use App\Models\User;
use App\UseCases\CalculationOfTotalMarksAverageAndRank;
use App\UserRoles;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CalculationOfTotalMarksAverageAndRankTest extends TestCase
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

        $this->teacher = Teacher::factory()->count(5)->create();
        $this->user = User::factory()->create([
            'teacher_id' => $this->teacher[0]->getKey(),
            'name' => $this->teacher[0]->teacher_name
        ]);
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
            app(CalculationOfTotalMarksAverageAndRank::class)->execute($this->user);
        }catch (UserRoleException $e){
            $this->assertEquals("No user assignment for this user", $e->getMessage());
        }
    }

    /** @test */
    public function when_mark_sheet_empty_then_exception_throw(): void
    {
        try {
            app(CalculationOfTotalMarksAverageAndRank::class)->execute($this->user);
        }catch (UserRoleException $e){
            $this->assertEquals("No relevant mark sheet data available", $e->getMessage());
        }
    }
}
