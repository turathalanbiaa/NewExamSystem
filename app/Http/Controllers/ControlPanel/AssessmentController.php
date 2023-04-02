<?php

namespace App\Http\Controllers\ControlPanel;

use App\Enums\AccountType;
use App\Enums\CourseState;
use App\Enums\EventLogType;
use App\Models\Assessment;
use App\Models\Course;
use App\Models\EduStudent;
use App\Models\EventLog;
use App\Models\Student;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\View\View;

class AssessmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $course
     * @return Factory|View
     */
    public function index($course)
    {
        Auth::check();
        $course = Course::findOrFail($course);
        CourseController::watchCourse($course);
        $assessments = Assessment::where("course_id", $course->id)
            ->get();

        return view("ControlPanel.assessment.index")->with([
            "course"      => $course,
            "assessments" => $assessments
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $course
     * @return Factory|View
     */
    public function create($course)
    {
        Auth::check();
        $course = Course::findOrFail($course);
        CourseController::watchCourse($course);

        $noOfStudentsEnrolled = Student::query()
            ->whereIn("edu_student_id", EduStudent::query()
                ->where("Level", $course->level)
                ->pluck("ID")
                ->toArray())
            ->count();

        $noOfResidentStudents =  Student::query()
            ->whereIn("id", Assessment::query()
                ->where("course_id", $course->id)
                ->pluck("student_id")
                ->toArray())
            ->count();

        $students = Student::query()
            ->whereIn("edu_student_id", EduStudent::query()
                ->where("Level", $course->level)
                ->pluck("ID")
                ->toArray())
            ->whereNotIn("id", Assessment::query()
                ->where("course_id", $course->id)
                ->pluck("student_id")
                ->toArray())
            ->take(10)
            ->get();

        return view("ControlPanel.assessment.create")->with([
            "course"   => $course,
            "students" => $students,
            "noOfStudentsEnrolled" => $noOfStudentsEnrolled,
            "noOfResidentStudents" => $noOfResidentStudents,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $course
     * @return RedirectResponse
     */
    public function store($course)
    {
        Auth::check();
        $course = Course::findOrFail($course);
        CourseController::watchCourse($course);

        $exception = DB::transaction(function () use ($course) {
            $studentsIDs = explode(",", Input::get("students"));

            $students = Student::query()
                ->whereIn("id", $studentsIDs)
                ->get();

            foreach ($students as $student) {
                if ($student->originalStudent->Level != $course->level)
                    continue;

                $assessment = Assessment::updateOrCreate([
                    'student_id' => $student->id,
                    'course_id' => $course->id
                ], [
                    'score' => min(abs(Input::get("student-".$student->id)), 25)
                ]);

                //Store event log
                $target = $assessment->id;
                $type = EventLogType::ASSESSMENT;
                $event = "تقييم الطالب " . $student->originalStudent->Name . " في " .$course->name . " هو " . $assessment->score;
                EventLog::create($target, $type, $event);
            }
        });

        if (is_null($exception))
            return redirect("/control-panel/assessments/$course->id/create")->with([
                "CreateAssessmentMessage" => "تم تققيم الطلاب بنجاح"
            ]);
        else
            return redirect("/control-panel/assessments/$course->id/create")->with([
                "CreateAssessmentMessage" => "لم يتم تققيم الطلاب بنجاح",
                "TypeMessage" => "Error"
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $course
     * @return RedirectResponse
     */
    public function evaluation($course)
    {
        Auth::check();
        $course = Course::findOrFail($course);
        CourseController::watchCourse($course);

        if (!is_numeric(Input::get("score")))
            return redirect("/control-panel/assessments/$course->id/create")->with([
                "EvaluationMessage" => "يرجى ملئ الدرجه"
            ]);

        $query = Student::query()
            ->whereIn("edu_student_id", EduStudent::query()
                ->where("Level", $course->level)
                ->pluck("ID")
                ->toArray());

        if (Input::get("tab") == "all-remaining-students")
            $query->whereNotIn("id", Assessment::query()
                ->where("course_id", $course->id)
                ->pluck("student_id")
                ->toArray());

        $students = $query->get();

        if (count($students) == 0)
            return redirect("/control-panel/assessments/$course->id/create")->with([
                "CreateAssessmentMessage" => "لا يوجد طلاب مسجلين على هذه المادة لتقييمهم",
                "TypeMessage" => "Error"
            ]);

        //Transaction
        $exception = DB::transaction(function () use ($course, $students) {
            //Store assessment for students
            $score = abs(Input::get("score"));
            foreach ($students as $student)
                Assessment::updateOrCreate(
                    ['student_id' => $student->id, 'course_id' => $course->id],
                    ['score' => min(abs(Input::get("score")), 25)]
                );

            //Store event log
            $target = $course->id;
            $type = EventLogType::ASSESSMENT;
            $event = "تقييم جميع الطلاب في " .$course->name . " هو " . $score;
            EventLog::create($target, $type, $event);
        });

        if (is_null($exception))
            return redirect("/control-panel/assessments/$course->id/create")->with([
                "CreateAssessmentMessage" => "تم تقييم جميع الطلاب"
            ]);
        else
            return redirect("/control-panel/assessments/$course->id/create")->with([
                "CreateAssessmentMessage" => "لم يتم تقييم جميع الطلاب",
                "TypeMessage" => "Error"
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $course
     * @param $assessment
     * @return RedirectResponse
     */
    public function update($course, $assessment)
    {
        Auth::check();
        $course = Course::findOrFail($course);
        CourseController::watchCourse($course);
        $assessment = Assessment::findOrFail($assessment);
        $student = $assessment->student;

        //Transaction
        $exception = DB::transaction(function () use ($course, $assessment, $student) {
            //Update assessment
            $assessment->score = min(abs(Input::get("score")), 25);
            $assessment->save();

            //Store event log
            $target = $assessment->id;
            $type = EventLogType::ASSESSMENT;
            $event = "تعديل تقييم الطالب " . $student->originalStudent->Name . " في " .$course->name . " هو " . $assessment->score;
            EventLog::create($target, $type, $event);
        });

        if (is_null($exception))
            return redirect("/control-panel/assessments/$course->id")->with([
                "UpdateAssessmentMessage" => "تم تعديل تقييم الطالب " . $student->originalStudent->Name . " بنجاح"
            ]);
        else
            return redirect("/control-panel/assessments/$course->id/create")->with([
                "UpdateAssessmentMessage" => "لم يتم تعديل تقييم الطالب " . $student->originalStudent->Name,
                "TypeMessage" => "Error"
            ]);
    }
}
