<?php

namespace App\Http\Controllers\ControlPanel;

use App\Models\Course;
use App\Models\ExamStudent;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AssessmentController extends Controller
{
    public function create($course)
    {
        $course = Course::findOrFail($course);
        $examsIds = $course->exams()
            ->pluck("id")
            ->toArray();

        $studentsIds = ExamStudent::whereIn("exam_id", $examsIds)
            ->pluck("student_id")
            ->toArray();
        $students = Student::whereIn("id", $studentsIds)
            ->take(10)
            ->get();

        return view("ControlPanel.assessment.create")->with([
            "course"   => $course,
            "students" => $students
        ]);
    }

    public function save()
    {

    }
}
