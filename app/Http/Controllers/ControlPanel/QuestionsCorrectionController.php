<?php

namespace App\Http\Controllers\ControlPanel;

use App\Enums\AnswerCorrectionState;
use App\Enums\EventLogType;
use App\Enums\ExamState;
use App\Enums\QuestionCorrectionState;
use App\Enums\QuestionType;
use App\Models\Answer;
use App\Models\EventLog;
use App\Models\Question;
use App\Models\Student;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class QuestionsCorrectionController extends Controller
{
    public function QuestionCorrectionAutomatically($id)
    {
        Auth::check();
        $question = Question::findOrFail($id);
        $exam = $question->exam;
        ExamController::watchExam($exam);

        //Check if exam is not end
        if ($exam->state != ExamState::END)
            return redirect("/control-panel/exams/$exam->id")->with([
                "QuestionCorrectionMessage" => "لا يمكن تصحيح السؤال لان الامتحان الحالي غير منتهي",
                "TypeMessage" => "Error"
            ]);

        //Check if question type is eligible for correction automatically
        if (($question->type == QuestionType::FILL_BLANK) || ($question->type == QuestionType::EXPLAIN))
            return redirect("/control-panel/exams/$exam->id")->with([
                "QuestionCorrectionMessage" => "لايمكن تصحيح السؤال:  " . $question->title . " تلقائياً",
                "TypeMessage" => "Error"
            ]);

        //Check if question is already correction
        if ($question->correction == QuestionCorrectionState::CORRECTED)
            return redirect("/control-panel/exams/$exam->id")->with([
                "QuestionCorrectionMessage" => "تم تصحيح السؤال:  " . $question->title . " مسبقاً",
                "TypeMessage" => "Error"
            ]);

        //Auto correction
        $exception = DB::transaction(function () use ($question, $exam){
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

        if (is_null($exception))
            return redirect("/control-panel/exams/$exam->id")->with([
                "QuestionCorrectionMessage" => "تم تصحيح السؤال:  " . $question->title
            ]);
        else
            return redirect("/control-panel/exams/$exam->id")->with([
                "QuestionCorrectionMessage" => "لم يتم تصحيح السؤال: " . $question->title,
                "TypeMessage" => "Error"
            ]);
    }

    public function ShowQuestionToCorrectionManually($id)
    {
        Auth::check();
        $question = Question::findOrFail($id);
        $exam = $question->exam;
        ExamController::watchExam($exam);

        //Check if exam is not end
        if ($exam->state != ExamState::END)
            return redirect("/control-panel/exams/$exam->id")->with([
                "QuestionCorrectionMessage" => "لا يمكن تصحيح السؤال لان الامتحان الحالي غير منتهي",
                "TypeMessage" => "Error"
            ]);

        //Check if question type is eligible for correction manually
        if (($question->type == QuestionType::TRUE_OR_FALSE) || ($question->type == QuestionType::SINGLE_CHOICE))
            return redirect("/control-panel/exams/$exam->id")->with([
                "QuestionCorrectionMessage" => "لايمكن تصحيح السؤال:  " . $question->title . "يَدَوياً",
                "TypeMessage" => "Error"
            ]);

        //Check if question is already correction
        if ($question->correction == QuestionCorrectionState::CORRECTED)
            return redirect("/control-panel/exams/$exam->id")->with([
                "QuestionCorrectionMessage" => "تم تصحيح السؤال:  " . $question->title . " مسبقاً",
                "TypeMessage" => "Error"
            ]);

        //Get answers group by student
        $studentsAnswers = Answer::whereIn("branch_id", $question->branches->Pluck("id")->toArray())
            ->where("correction", AnswerCorrectionState::UNCORRECTED)
            ->orderBy("student_id")
            ->get()
            ->groupBy("student_id")
            ->take(25);

        //Not have answers, Make question is corrected
        if ($studentsAnswers->isEmpty())
        {
            //Transaction
            $exception = DB::transaction(function () use ($exam, $question){
                //Make question is corrected
                $question->correction = QuestionCorrectionState::CORRECTED;
                $question->save();

                //Store event log
                $target = $question->id;
                $type = EventLogType::QUESTION;
                $event = "تصحيح السؤال: " . $question->title . "التابع للامتحان: " . $exam->title;
                EventLog::create($target, $type, $event);
            });

            if (is_null($exception))
                return redirect("/control-panel/exams/$exam->id")->with([
                    "QuestionCorrectionMessage" => "تم تصحيح السؤال:  " . $question->title
                ]);
            else
                return redirect("/control-panel/exams/$exam->id")->with([
                    "QuestionCorrectionMessage" => "لم يتم تصحيح السؤال: " . $question->title,
                    "TypeMessage" => "Error"
                ]);
        }

        //Marge student with your answers in collection
        $index = 0;
        $studentsCollections = array();
        foreach ($studentsAnswers as $studentAnswers)
        {
            $student = Student::find($studentAnswers[0]->student_id);
            $studentsCollections[$index++] = array("student" => $student, "answers" => $studentAnswers);
        }

        return view("ControlPanel.questionCorrection.show")->with([
            "studentsCollections" => $studentsCollections,
            "exam" => $exam,
            "question" => $question
        ]);
    }

    public function QuestionCorrectionManually()
    {
        Auth::check();
        $question = Question::findOrFail(Input::get("question"));
        $answers = Input::get("answers");
        $maxScoreForBranch = ($question->score / $question->no_of_branch_req);

        //Transaction
        $exception = DB::transaction(function () use ($answers, $maxScoreForBranch){
            foreach ($answers as $answer)
            {
                Answer::where("id", $answer["id"])
                    ->where("re_correct", 0)
                    ->update(
                        array(
                            "score" => ($answer["score"] > $maxScoreForBranch)?$maxScoreForBranch:abs($answer["score"]),
                            "correction" => AnswerCorrectionState::CORRECTED
                        )
                    );
            }
        });

        if (is_null($exception))
            return ["correction" => "success"];
        else
            return ["correction" => "fail"];
    }
}
