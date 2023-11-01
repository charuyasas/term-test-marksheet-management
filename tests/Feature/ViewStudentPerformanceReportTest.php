<?php

namespace Tests\Feature;

use App\Exceptions\UserRoleException;
use App\UseCases\ViewStudentPerformanceUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewStudentPerformanceReportTest extends TestCase
{
    public function when_unauthorized_user_tried_to_access_report_report_not_visible(): void
    {
        try {
            $report = app(ViewStudentPerformanceUseCase::class)->execute();
        }catch (UserRoleException $e){
            $this->assertEquals("No user assignment for this user", $e->getMessage());
        }
    }
}
