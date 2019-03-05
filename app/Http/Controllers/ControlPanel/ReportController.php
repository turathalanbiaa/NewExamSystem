<?php

namespace App\Http\Controllers\ControlPanel;

use App\Models\ExamStudent;
use App\Models\Student;
use App\Models\StudentDocument;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function index()
    {
        return view("ControlPanel.report.index");
    }

    public function showStudents()
    {
        $students = Student::all();
        return view("ControlPanel.report.students")->with([
            "students" => $students
        ]);
    }

    public function showStudent($student)
    {
        $student = Student::findOrFail($student);

        $documents = StudentDocument::where("student_id", $student->id)
            ->get();
        return view("ControlPanel.report.student")->with([
            "student" => $student,
            "documents" => $documents
        ]);
    }

    public function showCourses()
    {}

    public function showCourse($course)
    {}

    public function showExams()
    {}

    public function showExam($exam)
    {}
}
