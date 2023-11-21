<?php

namespace App\Reports;

use Illuminate\Support\Collection;

class StudentPerformanceReport
{
    public function __construct(
        protected Collection $markSheet,
        protected Collection $grading
    )
    {
    }

    public function generate(): Collection
    {
        $studentPerformance = new Collection;
        $studentPerformance->add([
            'subject_marks' => $this->getSubjectMarks(),
            'students_marks' => $this->getStudentMarkSheet()
        ]);

        return $studentPerformance;
    }

    public function getSubjectMarks(): Collection
    {
        $subjects = $this->markSheet->unique('subject_id');
        $subjectGrading = new Collection;

        foreach ($subjects as $subject) {

            $marksGrading = new Collection;

            foreach ($this->grading as $grade) {
                $marksGrading->add([
                    $grade->grading => $this->markSheet
                        ->whereBetween('marks', [$grade->min, $grade->max])
                        ->where('subject_id', $subject->subject_id)
                        ->count(),
                    'color_code' => $grade->color_code
                ]);
            }

            $subjectGrading->add([
                'subject_id' => $subject->subject_id,
                'subject_name' => $subject->subject_name,
                'academic_year' => $subject->academic_year,
                'term' => $subject->term,
                'marks_grading' => $marksGrading
            ]);
        }

        return $subjectGrading;
    }

    public function getStudentMarkSheet(): Collection
    {
        $students = $this->markSheet->unique('student_id');
        $studentMarks = new Collection;

        foreach ($students as $student) {
            $marks = new Collection;
            $totalMarks = 0;

            foreach ($this->markSheet->where('student_id', $student->student_id) as $mark) {
                $marks->add([
                    'subject_id' => $mark->subject_id,
                    'subject_name' => $mark->subject_name,
                    'marks' => $mark->marks,
                ]);
                $totalMarks += $mark->marks;
            }

            $studentMarks->add([
                'teacher_name' => $student->teacher_name,
                'admission_number' => $student->admission_number,
                'student_name' => $student->student_name,
                'gender' => $student->gender,
                'class_room' => $student->class_room,
                'academic_year' => $student->academic_year,
                'term' => $student->term,
                'marks' => $marks,
                'total' => $totalMarks,
                'average' => $totalMarks / $this->markSheet->where('student_id', $student->student_id)->count(),
            ]);
        }

        return $studentMarks->sortByDesc('total');
    }
}
