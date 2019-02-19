<?php

namespace App\Http\Controllers\ControlPanel;

use App\Enums\AnswerCorrectionState;
use App\Enums\QuestionType;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QuestionsCorrectionController extends Controller
{
    public function Correction($id)
    {
        Auth::check();
        $question = Question::findOrFail($id);
        ExamController::watchExam($question->exam);

        if (($question->type == QuestionType::TRUE_OR_FALSE) || ($question->type == QuestionType::SINGLE_CHOICE))
            return self::autoCorrection($question);
        else
            return self::manualCorrection($question);
    }

    public static function autoCorrection($question)
    {
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
    }

    public static function manualCorrection($question)
    {

    }
}
