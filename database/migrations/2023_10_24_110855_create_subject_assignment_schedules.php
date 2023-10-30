<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subject_assignment_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_id')->constrained('grades','id');
            $table->foreignId('class_id')->constrained('class_rooms','id');
            $table->foreignId('subject_id')->constrained('subjects','id');
            $table->foreignId('teacher_id')->constrained('teachers','id');
            $table->string('academic_year');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subject_assignment_schedules');
    }
};
