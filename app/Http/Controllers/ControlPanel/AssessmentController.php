<?php

namespace App\Http\Controllers\ControlPanel;

use App\Enums\AccountType;
use App\Enums\CourseState;
use App\Enums\EventLogType;
use App\Models\Assessment;
use App\Models\Course;
use App\Models\EventLog;
use App\Models\ExamStudent;
use App\Models\Student;
use Illuminate\Http\Request;
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
        self::watchCourse($course);

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
        self::watchCourse($course);

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
        self::watchCourse($course);

        if (!is_numeric(Input::get("score")))
            return redirect("/control-panel/assessments/$course->id/create")->with([
                "StoreAllMessage" => "يرجى ملئ الدرجه"
            ]);

        //Get exams ids for the course
        $examsIds = $course->exams()
            ->pluck("id")
            ->toArray();

        //Get students ids are enrolled to the course
        $studentsEnrolled = ExamStudent::whereIn("exam_id", $examsIds)
            ->distinct("student_id")
            ->pluck("student_id")
            ->toArray();

        if (count($studentsEnrolled) == 0)
            return redirect("/control-panel/assessments/$course->id/create")->with([
                "CreateAssessmentMessage" => "لا يوجد طلاب مسجلين على هذه المادة لتقييمهم",
                "TypeMessage" => "Error"
            ]);

        //Transaction
        $exception = DB::transaction(function () use ($course, $studentsEnrolled) {
            //Store assessment for all students
            $score = abs(Input::get("score"));
            foreach ($studentsEnrolled as $student)
            {
                //Get student
                $student = Student::findOrFail($student);

                //Store assessment for student
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
        self::watchCourse($course);
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
