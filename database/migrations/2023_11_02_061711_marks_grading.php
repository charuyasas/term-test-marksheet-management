<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marks_gradings', function (Blueprint $table) {
            $table->id();
            $table->string('grading');
            $table->decimal('min');
            $table->decimal('max');
            $table->string('color_code');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mark_sheet');
    }
};
