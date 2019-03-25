<?php

namespace App\Http\Controllers\ControlPanel;

use App\Enums\EventLogType;
use App\Enums\ExamType;
use App\Models\Assessment;
use App\Models\EventLog;
use App\Models\ExamStudent;
use App\Models\Student;
use App\Models\StudentDocument;
use App\Models\SystemVariables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
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
        Auth::check();
        $sys_vars = SystemVariables::find(1);

        //Transaction
        $exception = DB::transaction(function () use ($sys_vars){
            //Relay students' grades in exams to their documents
            $examsStudents = ExamStudent::all();
            foreach ($examsStudents as $examStudent)
            {
                $document = StudentDocument::where('student_id', $examStudent->student->id)
                    ->where('course_id', $examStudent->exam->course->id)
                    ->where('season', $sys_vars->current_season)
                    ->where('year', $sys_vars->current_year)
                    ->first();

                //Update document
                if ($document)
                {
                    $document->first_month_score = ($examStudent->exam->type == ExamType::FIRST_MONTH)?$examStudent->score:$document->first_month_score;
                    $document->second_month_score = ($examStudent->exam->type == ExamType::SECOND_MONTH)?$examStudent->score:$document->second_month_score;
                    $document->final_first_score = ($examStudent->exam->type == ExamType::FINAL_FIRST_ROLE)?$examStudent->score:$document->final_first_score;
                    $document->final_second_score = ($examStudent->exam->type == ExamType::FINAL_SECOND_ROLE)?$examStudent->score:$document->final_second_score;
                }

                //Create document
                if (!$document)
                {
                    $document = new StudentDocument();
                    $document->student_id = $examStudent->student->id;
                    $document->course_id = $examStudent->exam->course->id;
                    $document->first_month_score = ($examStudent->exam->type == ExamType::FIRST_MONTH)?$examStudent->score:null;
                    $document->second_month_score = ($examStudent->exam->type == ExamType::SECOND_MONTH)?$examStudent->score:null;
                    $document->assessment_score = null;
                    $document->final_first_score = ($examStudent->exam->type == ExamType::FINAL_FIRST_ROLE)?$examStudent->score:null;
                    $document->final_second_score = ($examStudent->exam->type == ExamType::FINAL_SECOND_ROLE)?$examStudent->score:null;
                    $document->total = null;
                    $document->decision_score = null;
                    $document->final_score = null;
                    $document->season = $sys_vars->current_season;
                    $document->year = $sys_vars->current_year;
                }

                //Store document
                $document->save();

                //Store event log
                $target = $document->id;
                $type = EventLogType::DOCUMENT;
                $event = "ترحيل درجة الطالب " . $examStudent->student->originalStudent->Name . " في امتحان " . $examStudent->exam->title . " الى وثيقته";
                EventLog::create($target, $type, $event);
            }

            //Relay students' grades in assessment to their documents
            $assessments = Assessment::all();
            foreach ($assessments as $assessment)
            {
                $document = StudentDocument::updateOrCreate(
                    [
                        "student_id" => $assessment->student_id,
                        "course_id"  => $assessment->course_id,
                        'season'     => $sys_vars->current_season,
                        'year'       => $sys_vars->current_year
                    ],
                    [
                        "assessment_score" => $assessment->score
                    ]
                );

                //Store event log
                $target = $document->id;
                $type = EventLogType::DOCUMENT;
                $event = "ترحيل درجة تقييم الطالب " . $assessment->student->originalStudent->Name . " في المادة " . $assessment->course->name . " الى وثيقته";
                EventLog::create($target, $type, $event);
            }

            //documents
            $documents = StudentDocument::where("season", $sys_vars->current_season)
                ->where("year", $sys_vars->current_year)
                ->get();
            foreach ($documents as $document)
            {
                //Find total
                $total = $document->first_month_score + $document->second_month_score + $document->assessment_score;
                if (is_null($document->final_second_score))
                    $total += $document->final_first_score;
                else
                    $total += $document->final_second_score;
                $total = ceil($total);
                $document->total = $total;

                //Find final score
                $document->final_score = $total;
                if ($total == 50)
                {
                    $document->final_score = $total + 1;
                    $document->decision_score = null;
                }

                $document->save();
            }
        });

        if (is_null($exception))
            return redirect("/control-panel/documents")->with([
                "DocumentCreationMessage" => "تم ترحيل درجات الطلاب الى وثائقهم"
            ]);
        else
            return redirect("/control-panel/documents")->with([
                "DocumentCreationMessage" => "لم يتم ترحيل درجات الطلاب الى وثائقهم",
                "TypeMessage" => "Error"
            ]);
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

    public function show($student)
    {
        Auth::check();
        $student = Student::findOrFail($student);

        $documentsGroupingByYear = $student->documents->groupBy("year");
        foreach ($documentsGroupingByYear as $documents)
        {
            $documentsGroupingBySeason = $documents->groupBy("season");
        }

        return view("ControlPanel.document.show")->with([
            "student" => $student
        ]);
    }
}
