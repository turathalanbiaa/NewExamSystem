<?php

namespace App\Http\Controllers\ControlPanel;

use App\Enums\CourseState;
use App\Enums\EventLogType;
use App\Enums\ExamType;
use App\Models\Assessment;
use App\Models\Course;
use App\Models\EduStudent;
use App\Models\EventLog;
use App\Models\Exam;
use App\Models\ExamStudent;
use App\Models\Student;
use App\Models\StudentDocument;
use App\Models\SystemVariables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
    public function __construct()
    {
        ini_set('max_execution_time', 1000);
    }

    /**
     * Display all operation
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        Auth::check();
        return view("ControlPanel.document.index");
    }

    /**
     * Relay students' grades to their documents
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function creation()
    {
        ini_set('max_execution_time', 3000);
        Auth::check();
        $sys_vars = SystemVariables::find(1);

        try {
            // Get all students
            $students = Student::query()
                ->has("originalStudent")
                ->with("originalStudent")
                ->get();

            foreach ($students as $student) {
                if (!$student->originalStudent->Level)
                    continue;

                // localhost
//                $student = Student::find(3536);

                // Get all courses, exams, assessment.
                $courses = Course::query()
                    ->has("exams")
                    ->where("level", $student->originalStudent->Level)
                    ->with(["exams" => function ($query) use ($student) {
                        $query->with(["studentsEnrolled" => function ($query) use ($student) {
                            $query->where("student_id", $student->id);
                        }]);
                    }])
                    ->with(['assessments' => function($query) use ($student) {
                        $query->where("student_id", $student->id);
                    }])
                    ->get();

                // localhost
//                dd($courses->toArray());

                // Update or create student document
                foreach ($courses as $course) {
                    $assessmentScore = $course->assessments->first()->score ?? null;
                    $finalScoreFirstRole = $this::getExamScore($course, ExamType::FINAL_FIRST_ROLE);
                    $finalScoreSecondRole = $this::getExamScore($course, ExamType::FINAL_SECOND_ROLE);
                    $totalScore = ceil($assessmentScore + max($finalScoreFirstRole, $finalScoreSecondRole));
                    $finalScore = ($totalScore == 50) ? $totalScore+=1 : $totalScore;

                    // localhost
//                    dump($course->id.":A=".$assessmentScore.":FF=".$finalScoreFirstRole.":FS=".$finalScoreSecondRole.":T=".$totalScore.":F=".$finalScore);

                    // create or update document
                    StudentDocument::query()->updateOrCreate([
                        "course_id" => $course->id,
                        "student_id" => $student->id,
                        "season" => $sys_vars->current_season,
                        "year" => $sys_vars->current_year,
                    ], [
                        "first_month_score" => null,
                        "second_month_score" => null,
                        "assessment_score" => $assessmentScore,
                        "final_first_score" => $finalScoreFirstRole,
                        "final_second_score" => $finalScoreSecondRole,
                        "total" => $totalScore,
                        "decision_score" => 0,
                        "final_score" => $finalScore,
                    ]);
                }

                // localhost
//                dd("Done Student ID: 3536", $courses->toArray());
            }
        } catch (\Exception $exception) {
            return redirect("/control-panel/documents")->with([
                "DocumentCreationMessage" => "لم يتم ترحيل درجات الطلاب الى وثائقهم",
                "TypeMessage" => "Error"
            ]);
        }

        return redirect("/control-panel/documents")->with([
            "DocumentCreationMessage" => "تم ترحيل درجات الطلاب الى وثائقهم"
        ]);
    }

    public static function getExamScore($course, $examType) {
        $exam = $course->exams
            ->where("type", $examType)
            ->first();

        if (!$exam)
            return null;

        $studentEnrolled = $exam->studentsEnrolled->first();

        if (!$studentEnrolled)
            return null;

        return $studentEnrolled->score;
    }

    /**
     * Display students
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search()
    {
        Auth::check();
        $students = Student::all();
        return view("ControlPanel.document.search")->with([
            "students" => $students
        ]);
    }

    /**
     * Display documents for student
     *
     * @param $student
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($student)
    {
        Auth::check();
        $student = Student::findOrFail($student);

        return view("ControlPanel.document.show")->with([
            "student" => $student
        ]);
    }

    public function exportDocument($type)
    {
        Auth::check();

        if ($type == "levels")
            return view("ControlPanel.document.export.level.index");

        if ($type == "courses")
            return view("ControlPanel.document.export.course.index")->with([
               "courses" => CourseController::getCoursesOpen()
            ]);

        if ($type == "exams")
            return view("ControlPanel.document.export.exam.index")->with([
                "exams" => Exam::all()->filter(function ($exam){
                    return ($exam->course->state == CourseState::OPEN);
                })
            ]);

        return abort(404);
    }

    public function showExportDocument($type, $value)
    {
        Auth::check();

        $value = (Integer) $value;
        if ($type == "level")
        {
            $courses = Course::where("level", $value)
                ->where("state", CourseState::OPEN)
                ->orderBy("id")
                ->get();

            $CoursesIdInDocuments = StudentDocument::whereIn("course_id", $courses->pluck('id')->toArray())->distinct()->pluck('course_id')->toArray();

            $coursesInDocuments = Course::whereIn("id", $CoursesIdInDocuments)
                ->where("state", CourseState::OPEN)
                ->orderBy("id")
                ->get();

            $students = Student::all();
            $students = $students->filter(function ($student) use ($value){
                return ($student->originalStudent && $student->originalStudent->Level == $value);
            });

            return view("ControlPanel.document.export.level.show")->with([
                "level" => $value,
                "courses" => $coursesInDocuments,
                "students" => $students,
                "numberOfCourses" => count($CoursesIdInDocuments)
            ]);
        }

        if ($type == "course")
        {
            $course = Course::findOrFail($value);
            $students = Student::all();
            $students = $students->filter(function ($student) use ($course){
                return ($student->originalStudent && $student->originalStudent->Level == $course->level);
            });

            return view("ControlPanel.document.export.course.show")->with([
                "course" => $course,
                "students" => $students
            ]);
        }

        if ($type == "exam")
        {
            $exam = Exam::findOrFail($value);
            $students = Student::all();
            $students = $students->filter(function ($student) use ($exam){
                return ($student->originalStudent && $student->originalStudent->Level == $exam->course->level);
            });

            return view("ControlPanel.document.export.exam.show")->with([
                "exam" => $exam,
                "students" => $students
            ]);
        }

        return abort(404);
    }
}
