<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mark_sheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students','id');
            $table->foreignId('subject_id')->constrained('subjects','id');
            $table->foreignId('class_id')->constrained('class_rooms','id');
            $table->foreignId('teacher_id')->constrained('teachers','id');
            $table->integer('term');
            $table->string('academic_year');
            $table->decimal('marks');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mark_sheet');
    }
};
