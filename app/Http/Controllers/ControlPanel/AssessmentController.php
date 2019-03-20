<?php

namespace App\Http\Controllers\ControlPanel;

use App\Enums\AccountType;
use App\Enums\CourseState;
use App\Enums\EventLogType;
use App\Models\Assessment;
use App\Models\Course;
use App\Models\EventLog;
use App\Models\Student;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class AssessmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $course
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($course)
    {
        Auth::check();
        $course = Course::findOrFail($course);
        CourseController::watchCourse($course);

        //Get students
        $students = Student::all();

        //Get students are resident for the specific course
        $studentsIdsResident = Assessment::where("course_id", $course->id)
            ->pluck("student_id")
            ->toArray();

        //Get students are not resident for the specific course
        $students = $students->filter(function ($student) use ($course, $studentsIdsResident) {
            return (!in_array($student->id, $studentsIdsResident) && $student->originalStudent->Level == $course->level);
        });

        $noOfStudentsResident = count($studentsIdsResident);
        $noOfStudentsEnrolled = count($studentsIdsResident) + count($students);
        $students = $students->take(25);


        return view("ControlPanel.assessment.create")->with([
            "course"   => $course,
            "students" => $students,
            "noOfStudentsEnrolled" => $noOfStudentsEnrolled,
            "noOfStudentsResident" => $noOfStudentsResident,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $course
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($course)
    {
        Auth::check();
        $course = Course::findOrFail($course);
        CourseController::watchCourse($course);

        //Transaction
        $exception = DB::transaction(function () use ($course) {
            $students = Input::get("students");
            foreach (json_decode($students) as $student)
            {
                //Get student
                $student = Student::findOrFail($student);

                //Store assessment
                $score = abs(Input::get($student->id));
                $assessment = Assessment::updateOrCreate(
                    ['student_id' => $student->id, 'course_id' => $course->id],
                    ['score' => ($score<=15)?$score:15]
                );

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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeAll($course)
    {
        Auth::check();
        $course = Course::findOrFail($course);
        CourseController::watchCourse($course);

        if (!is_numeric(Input::get("score")))
            return redirect("/control-panel/assessments/$course->id/create")->with([
                "StoreAllMessage" => "يرجى ملئ الدرجه"
            ]);

        //Get students
        $students = Student::all();

        //Get students for the specific course
        $students = $students->filter(function ($student) use ($course) {
            return ($student->originalStudent->Level == $course->level);
        });

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
            {
                Assessment::updateOrCreate(
                    ['student_id' => $student->id, 'course_id' => $course->id],
                    ['score' => ($score<=15)?$score:15]
                );
            }

            //Store event log
            $target = $course->id;
            $type = EventLogType::ASSESSMENT;
            $event = "تقييم جميع الطلاب في " .$course->name . " هو " . $score;
            EventLog::create($target, $type, $event);
        });

        if (is_null($exception))
            return redirect("/control-panel/assessments/$course->id/create")->with([
                "CreateAssessmentMessage" => "تم تقييم جميع الطلاب بدرجه متساوية"
            ]);
        else
            return redirect("/control-panel/assessments/$course->id/create")->with([
                "CreateAssessmentMessage" => "لم يتم تقييم جميع الطلاب بدرجه متساوية",
                "TypeMessage" => "Error"
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $course
     * @param $assessment
     * @return \Illuminate\Http\RedirectResponse
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
            $score = abs(Input::get("score"));
            $assessment->score = ($score<=15)?$score:15;
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
