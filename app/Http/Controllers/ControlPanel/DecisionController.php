<?php

namespace App\Http\Controllers\ControlPanel;

use App\Enums\EventLogType;
use App\Enums\Level;
use App\Models\Course;
use App\Models\EventLog;
use App\Models\StudentDocument;
use App\Models\SystemVariables;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DecisionController extends Controller
{
    public function execute()
    {

        Auth::check();
        $sys_vars = SystemVariables::find(1);

        //Levels
        $levels = collect([
            Level::BEGINNER,
            Level::FIFTH,
            Level::SECOND,
            Level::THIRD,
            Level::FOURTH,
            Level::FIFTH,
            Level::SIXTH
        ]);

        //Transaction
        $exception = DB::transaction(function () use ($sys_vars, $levels) {
            //Execute decision system for the levels
            foreach ($levels as $level)
            {
                //Get courses for the level
                $courses = Course::where("level", $level)
                    ->pluck("id")
                    ->toArray();

                //Get students for the level
                $students = StudentDocument::whereIn("course_id", $courses)
                    ->where("season", $sys_vars->current_season)
                    ->where("year", $sys_vars->current_year)
                    ->get()
                    ->groupBy("student_id");

                //Total number of students for the level
                $totalNumberOfStudents = $students->count();

                //Stop loop when not found students for the level
                if ($totalNumberOfStudents == 0)
                    break;

                //Get not successful students
                $notSuccessfulStudents = collect();
                foreach ($students as $student)
                {
                    //Select total scores for courses to the student
                    $scores = $student->sortByDesc("total")->pluck("total");

                    //Number of failed courses for the student
                    $numberOfFailedCourses = $scores->filter(function ($score) {
                        return $score < 50;
                    })->count();

                    //Push student to not Successful students collection when student is not success
                    if ($numberOfFailedCourses != 0)
                        $notSuccessfulStudents->push($student);
                }

                //Execute decision system for not successful students
                foreach ($notSuccessfulStudents as $student)
                {
                    //Sort descending document
                    $documents = $student->sortByDesc("total");

                    //Get documents failed
                    $documentsFailed = $documents->filter(function ($document) {
                        return $document->total < 50;
                    });

                    //Execute decision system
                    $decisionScore = 5;
                    foreach ($documentsFailed as $document)
                    {
                        $document->final_score = (($document->total + $decisionScore)>=50)?50:$document->total;
                        $document->decision_score = $document->final_score - $document->total;
                        $decisionScore = $decisionScore - $document->decision_score;

                        //Stop loop when not have decision score
                        if ($decisionScore == 0)
                            break;
                    }

                    //Number of failed courses for the student
                    $numberOfFailedCourses = $documentsFailed->filter(function ($document) {
                        return $document->final_score < 50;
                    })->count();

                    //Update documents for student (successful or supplementary)
                    if ($numberOfFailedCourses < 3)
                        $documentsFailed->each(function ($document) {
                            $document->save();
                        });
                }
            }

            //Store event log
            $target = null;
            $type = EventLogType::DOCUMENT;
            $event = "تطبيق نظام القرار";
            EventLog::create($target, $type, $event);
        });

        if (is_null($exception))
            return redirect("/control-panel/documents")->with([
                "ExecuteDecisionSystemMessage" => "تم تطبيق نظام القرار"
            ]);
        else
            return redirect("/control-panel/documents")->with([
                "ExecuteDecisionSystemMessage" => "لم يتم تطبيق نظام القرار",
                "TypeMessage" => "Error"
            ]);
    }
















    public function xnxx($level)
    {
        //Get courses for the level
        $courses = Course::where("level", $level)
            ->pluck("id")
            ->toArray();

        //Get students for the level
        $students = StudentDocument::whereIn("course_id", $courses)
            ->where("season", $sys_vars->current_season)
            ->where("year", $sys_vars->current_year)
            ->get()
            ->groupBy("student_id");

        //Total number of students for the level
        $totalNumberOfStudents = $students->count();

        //Before execute decision system
        $notSuccessfulStudents = collect();
        $numberOfSuccessfulStudents = 0;
        $numberOfSupplementaryStudents = 0;
        $numberOfFailedStudents = 0;
        foreach ($students as $student)
        {
            //Select total scores for courses to the student
            $scores = $student->sortByDesc("total")->pluck("total");

            //Number of failed courses for the student
            $numberOfFailedCourses = $scores->filter(function ($score) {
                return $score < 50;
            })->count();

            //Check the student certificate (successful or supplementary or failed)
            if ($numberOfFailedCourses == 0)
                $numberOfSuccessfulStudents++;
            else
            {
                //Push supplementary student and failed student to not successful student collection
                $notSuccessfulStudents->push($student);
                if ($numberOfFailedCourses < 3)
                    $numberOfSupplementaryStudents++;
                else
                    $numberOfFailedStudents++;
            }
        }

        //ratio for success student
        $ratioSuccess = ($numberOfSuccessfulStudents/$totalNumberOfStudents)*100 . "%";

        //Result collection before execute decision system
        $resultBeforeExecuteDecisionSystem = collect([
            "numberOfSuccessfulStudents" => $numberOfSuccessfulStudents,
            "numberOfSupplementaryStudents" => $numberOfSupplementaryStudents,
            "numberOfFailedStudents" => $numberOfFailedStudents,
            "ratioSuccess" => $ratioSuccess
        ]);

        //After execute decision system
        //$numberOfSuccessfulStudents;
        $numberOfSupplementaryStudents = 0;
        $numberOfFailedStudents = 0;
        foreach ($notSuccessfulStudents as $student)
        {
            //Sort descending document
            $documents = $student->sortByDesc("total");

            //Get documents failed
            $documentsFailed = $documents->filter(function ($document) {
                return $document->total < 50;
            });

            //Execute decision system
            $decisionScore = 5;
            foreach ($documentsFailed as $document)
            {
                $document->final_score = (($document->total + $decisionScore)>=50)?50:$document->total;
                $document->decision_score = $document->final_score - $document->total;
                $decisionScore = $decisionScore - $document->decision_score;

                //Stop loop when not have decision score
                if ($decisionScore == 0)
                    break;
            }

            ///Number of failed courses for the student
            $numberOfFailedCourses = $documentsFailed->filter(function ($document) {
                return $document->final_score < 50;
            })->count();

            //Check the student certificate (successful or supplementary or failed)
            if ($numberOfFailedCourses >= 3)
                $numberOfFailedStudents++;
            else
            {
                if ($numberOfFailedCourses == 0)
                    $numberOfSuccessfulStudents++;
                else
                    $numberOfSupplementaryStudents++;

                $documentsFailed->each(function ($document) {
                    $document->save();
                });
            }
        }
        //ratio for success student
        $ratioSuccess = ($numberOfSuccessfulStudents/$totalNumberOfStudents)*100 . "%";

        //Result collection before execute decision system
        $resultAfterExecuteDecisionSystem = collect([
            "numberOfSuccessfulStudents" => $numberOfSuccessfulStudents,
            "numberOfSupplementaryStudents" => $numberOfSupplementaryStudents,
            "numberOfFailedStudents" => $numberOfFailedStudents,
            "ratioSuccess" => $ratioSuccess
        ]);

        return view("ControlPanel.decision.show")->with([
            "level" => $level,
            "totalNumberOfStudents" => $totalNumberOfStudents,
            "resultBeforeExecuteDecisionSystem" => $resultBeforeExecuteDecisionSystem,
            "resultAfterExecuteDecisionSystem" => $resultAfterExecuteDecisionSystem
        ]);
    }
}
