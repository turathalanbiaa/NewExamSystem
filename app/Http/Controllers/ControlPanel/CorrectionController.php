<?php

namespace App\Http\Controllers\ControlPanel;

use App\Enums\AnswerCorrectionState;
use App\Enums\EventLogType;
use App\Enums\ExamState;
use App\Enums\QuestionCorrectionState;
use App\Enums\QuestionType;
use App\Models\Answer;
use App\Models\EventLog;
use App\Models\Exam;
use App\Models\ExamStudent;
use App\Models\Question;
use App\Models\Student;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class CorrectionController extends Controller
{
    /**
     * Correction automatically for the question
     *
     * @param $question
     * @return \Illuminate\Http\RedirectResponse
     */
    public function CorrectionAutomatically($question)
    {
        Auth::check();
        $question = Question::findOrFail($question);
        $exam = $question->exam;
        ExamController::watchExam($exam);

        //Exam is not end
        if ($exam->state != ExamState::END)
            return redirect("/control-panel/exams/$exam->id")->with([
                "QuestionCorrectionMessage" => "لا يمكن تصحيح السؤال لان الامتحان الحالي غير منتهي",
                "TypeMessage" => "Error"
            ]);

        //Question type is eligible for correction automatically
        if (($question->type == QuestionType::FILL_BLANK) || ($question->type == QuestionType::EXPLAIN))
            return redirect("/control-panel/exams/$exam->id")->with([
                "QuestionCorrectionMessage" => "لايمكن تصحيح السؤال:  " . $question->title . " تلقائياً",
                "TypeMessage" => "Error"
            ]);

        //Question is already correction
        if ($question->correction == QuestionCorrectionState::CORRECTED)
            return redirect("/control-panel/exams/$exam->id")->with([
                "QuestionCorrectionMessage" => "تم تصحيح السؤال:  " . $question->title . " مسبقاً",
                "TypeMessage" => "Error"
            ]);

        //Transaction
        $exception = DB::transaction(function () use ($question, $exam){
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
            $event = "تصحيح السؤال: " . $question->title . "التابع للامتحان " . $exam->title . " تلقائياً";
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

    /**
     * Display the student answers for the question to correction manually
     *
     * @param $question
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function ShowQuestionToCorrectionManually($question)
    {
        Auth::check();
        $question = Question::findOrFail($question);
        $exam = $question->exam;
        ExamController::watchExam($exam);

        //Exam is not end
        if ($exam->state != ExamState::END)
            return redirect("/control-panel/exams/$exam->id")->with([
                "QuestionCorrectionMessage" => "لا يمكن تصحيح السؤال لان الامتحان الحالي غير منتهي",
                "TypeMessage" => "Error"
            ]);

        //Question type is eligible for correction manually
        if (($question->type == QuestionType::TRUE_OR_FALSE) || ($question->type == QuestionType::SINGLE_CHOICE))
            return redirect("/control-panel/exams/$exam->id")->with([
                "QuestionCorrectionMessage" => "لايمكن تصحيح السؤال:  " . $question->title . "يَدَوياً",
                "TypeMessage" => "Error"
            ]);

        //Question is already correction
        if ($question->correction == QuestionCorrectionState::CORRECTED)
            return redirect("/control-panel/exams/$exam->id")->with([
                "QuestionCorrectionMessage" => "تم تصحيح السؤال:  " . $question->title . " مسبقاً",
                "TypeMessage" => "Error"
            ]);

        //Get students answers
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
                $event = "تصحيح السؤال: " . $question->title . "التابع للامتحان " . $exam->title;
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

        return view("ControlPanel.correction.show")->with([
            "studentsCollections" => $studentsCollections,
            "exam" => $exam,
            "question" => $question
        ]);
    }

    /**
     * Correction manually the student answers for the question
     *
     * @return array
     */
    public function CorrectionManually()
    {
        Auth::check();
        $student = Student::findOrFail(Input::get("student"));
        $question = Question::findOrFail(Input::get("question"));
        $exam = $question->exam;
        $answers = Input::get("answers");
        $maxScoreForBranch = ($question->score / $question->no_of_branch_req);

        //Transaction
        $exception = DB::transaction(function () use ($student, $exam, $question, $answers, $maxScoreForBranch){
            foreach ($answers as $answer)
            {
                Answer::where("id", $answer["id"])
                    ->where("correction", "!=", AnswerCorrectionState::RE_CORRECTED)
                    ->update(
                        array(
                            "score" => ($answer["score"] > $maxScoreForBranch)?$maxScoreForBranch:abs($answer["score"]),
                            "correction" => AnswerCorrectionState::CORRECTED
                        )
                    );
            }

            //Store event log
            $target = $student->id;
            $type = EventLogType::QUESTION;
            $event = "تصحيح اجوبة الطالب " . $student->originalStudent->Name . " في السؤال: " . $question->title . "التابع للامتحان " . $exam->title;
            EventLog::create($target, $type, $event);
        });

        if (is_null($exception))
            return ["correction" => "success"];
        else
            return ["correction" => "fail"];
    }

    public function sum($exam)
    {
        Auth::check();
        $exam = Exam::findOrFail($exam);
        ExamController::watchExam($exam);

        //Questions are corrected for the exam
        if (!self::corrected($exam))
            return redirect("/control-panel/exams/$exam->id")->with([
                "SumMessage" => "لا يمكن جمع درجة الامتحان للطلاب لانه لم يكتمل تصحيح جميع الاسئلة"
            ]);

        //Transaction
        $exception = DB::transaction(function () use ($exam){
            //Find ratio
            $ratio = $exam->real_score / $exam->fake_score;

            //Students
            $examStudents = ExamStudent::where("exam_id", $exam->id)
                ->get();

            //Questions
            $questions = $exam->questions;

            //Find sum degree for each student
            foreach ($examStudents as $examStudent)
            {
                //Find sum degree
                $sum = 0;
                foreach ($questions as $question)
                {
                    $branchesIds = $question->branches()->pluck("id")->toArray();
                    $answers = Answer::where("student_id", $examStudent->student_id)
                        ->whereIn("branch_id", $branchesIds)
                        ->orderBy("score","DESC")
                        ->take($question->no_of_branch_req)
                        ->get();
                    $sum = $sum + $answers->sum("score");
                }

                //Convert fake score to real real score
                $sum = $sum * $ratio;

                //Add curve to sum
                $sum = $sum + $exam->curve;

                //Update exam student
                $examStudent->score = $sum;
                $examStudent->save();

                //Get student
                $student = Student::findOrFail($examStudent->student_id);

                //Store event log
                $target = $examStudent->id;
                $type = EventLogType::EXAM;
                $event = "جمع درجة الامتحان " . $exam->title . "للطالب " . $student->originalStudent->Name;
                EventLog::create($target, $type, $event);
            }
        });

        if (is_null($exception))
            return redirect("/control-panel/exams")->with([
                "SumMessage" => "تم جمع درجة الامتحان " . $exam->title . " بنجاح"
            ]);
        else
            return redirect("/control-panel/exams")->with([
                "SumMessage" => "لم يتم جمع درجة الامتحان " . $exam->title,
                "TypeMessage" => "Error"
            ]);
    }

    /**
     * Check Questions are corrected
     *
     * @param $exam
     * @return bool
     */
    private static function corrected($exam)
    {
        $questions = $exam->questions;
        foreach ($questions as $question)
            if ($question->correction != QuestionCorrectionState::CORRECTED)
                return false;
        return true;
    }
}
