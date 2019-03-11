<?php

namespace App\Http\Controllers\ControlPanel;

use App\Models\Assessment;
use App\Models\Course;
use App\Models\ExamStudent;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class AssessmentController extends Controller
{
    public function create($course)
    {
        Auth::check();
        $course = Course::findOrFail($course);
        self::watchCourse($course);

        //Get exams ids for the course
        $examsIds = $course->exams()
            ->pluck("id")
            ->toArray();

        //Get students ids are enrolled to the course
        $studentsEnrolled = ExamStudent::whereIn("exam_id", $examsIds)
            ->distinct("student_id")
            ->pluck("student_id")
            ->toArray();

        $noOfStudentsEnrolled = count($studentsEnrolled);

        //Get students ids are resident to the course
        $studentsResident = Assessment::where("course_id", $course->id)
            ->pluck("student_id")
            ->toArray();

        $noOfStudentsResident = count($studentsResident);

        //Get students ids are enrolled but not resident to the course
        $studentsIds = array_values(array_diff($studentsEnrolled, $studentsResident)) ;

        //Get students
        $students = Student::whereIn("id", $studentsIds)
            ->take(10)
            ->get();

        return view("ControlPanel.assessment.create")->with([
            "course"   => $course,
            "students" => $students,
            "noOfStudentsEnrolled" => $noOfStudentsEnrolled,
            "noOfStudentsResident" => $noOfStudentsResident,
        ]);
    }

    public function store($course)
    {
        Auth::check();
        $course = Course::findOrFail($course);
        self::watchCourse($course);

        //Transaction
        $exception = DB::transaction(function () {

        });
        dd(Input::all());
    }

    public function show()
    {

    }

    /**
     * Check can watch the specified course form storage
     *
     * @param $course
     */
    private static function watchCourse($course)
    {
        if(session()->get("EXAM_SYSTEM_ACCOUNT_TYPE") == AccountType::MANAGER)
            $courses = Course::where("state", CourseState::OPEN)
                ->get();
        else
            $courses = Course::where("state", CourseState::OPEN)
                ->where("lecturer_id", session()->get("EXAM_SYSTEM_ACCOUNT_ID"))
                ->get();

        if(!in_array($course->id, $courses->pluck("id")->toArray()))
            abort(404);
    }
}
