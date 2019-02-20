<?php

namespace App\Http\Controllers\ControlPanel;

use App\Enums\AnswerCorrectionState;
use App\Enums\QuestionType;
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
        ExamController::watchExam($question->exam);

        if (($question->type == QuestionType::TRUE_OR_FALSE) || ($question->type == QuestionType::SINGLE_CHOICE))
        {
            $success = self::autoCorrection($question);

            if ($success)
                $message = "تم تصحيح السؤال بنجاح";
            else
                $message = "لم يتم تصحيح السؤال";


            return view("ControlPanel.questionCorrection.finish")->with([
                "mess"
            ]);
        }



        return view("ControlPanel.questionCorrection.show")->with([

        ]);
    }

    public static function autoCorrection($question)
    {
        $exception = DB::transaction(function () use ($question){
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
        });

        return is_null($exception)?true:false;
    }

    public static function manualCorrection($question)
    {

    }

}
