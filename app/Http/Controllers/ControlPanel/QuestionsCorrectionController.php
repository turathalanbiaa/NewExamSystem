<?php

namespace App\Http\Controllers\ControlPanel;

use App\Enums\AnswerCorrectionState;
use App\Enums\EventLogType;
use App\Enums\QuestionCorrectionState;
use App\Enums\QuestionType;
use App\Models\Answer;
use App\Models\EventLog;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class QuestionsCorrectionController extends Controller
{
    public function showQuestionToCorrection($id)
    {
        Auth::check();
        $question = Question::findOrFail($id);
        $exam = $question->exam;
        ExamController::watchExam($exam);


        //Show answers
        $studentsAnswers = Answer::whereIn("branch_id", $question->branches->Pluck("id")->toArray())
            ->get()
            ->groupBy("student_id");

        return view("ControlPanel.questionCorrection.show")->with([
            "studentsAnswers" => $studentsAnswers,
            "exam" => $exam,
            "question" => $question
        ]);




        if ($question->correction == QuestionCorrectionState::CORRECTED)
            return redirect("/control-panel/exams/$exam->id")->with([
                "QuestionCorrectionMessage" => "تم تصحيح السؤال:  " . $question->title . "مسبقاً"
            ]);

        //Auto correction
        if (($question->type == QuestionType::TRUE_OR_FALSE) || ($question->type == QuestionType::SINGLE_CHOICE))
        {
            $success = self::autoCorrection($question);

            if ($success)
                return redirect("/control-panel/exams/$exam->id")->with([
                    "QuestionCorrectionMessage" => "تم تصحيح السؤال:  " . $question->title
                ]);
            else
                return redirect("/control-panel/exams/$exam->id")->with([
                    "QuestionCorrectionMessage" => "لم يتم تصحيح السؤال: " . $question->title,
                    "TypeMessage" => "Error"
                ]);
        }

        //Show answers
        $studentsAnswers = Answer::whereIn("branch_id", $question->branches->Pluck("id")->toArray())
            ->get()
            ->groupBy("student_id");

        dd($studentsAnswers);
        return view("ControlPanel.questionCorrection.show")->with([
            ""
        ]);
    }

    public static function autoCorrection($question)
    {
        $exception = DB::transaction(function () use ($question){
            $exam = $question->exam;

            //Correction answers
            foreach ($question->branches as $branch)
            {
                if ($branch->answers->isNotEmpty())
                {
                    $branch->answers()
                        ->where("correction", AnswerCorrectionState::UNCORRECTED)
                        ->where("text", $branch->correct_option)
                        ->update(array("score" => $branch->score));

                    $branch->answers()
                        ->update(array("correction" => AnswerCorrectionState::CORRECTED));
                }
            }

            //Make question is corrected
            $question->correction = QuestionCorrectionState::CORRECTED;
            $question->save();

            //Store event log
            $target = $question->id;
            $type = EventLogType::QUESTION;
            $event = "تصحيح السؤال: " . $question->title . "التابع للامتحان: " . $exam->title;
            EventLog::create($target, $type, $event);
        });

        return is_null($exception)?true:false;
    }

    public static function manualCorrection($question)
    {

    }
}
