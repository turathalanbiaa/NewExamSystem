<?php

namespace App\Http\Controllers\ControlPanel;

use App\Enums\AnswerCorrectionState;
use App\Enums\EventLogType;
use App\Enums\ExamState;
use App\Enums\QuestionCorrectionState;
use App\Models\Answer;
use App\Models\Branch;
use App\Models\EventLog;
use App\Models\Exam;
use App\Models\Marking;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpKernel\HttpCache\Esi;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        Auth::check();
        $exam = Exam::findOrFail(Input::get("exam"));
        ExamController::watchExam($exam);
        return view("ControlPanel.question.create")->with([
            "exam" => $exam
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        Auth::check();
        $exam = Exam::findOrFail(Input::get("exam"));
        ExamController::watchExam($exam);
        $remainingScore = $exam->fake_score - $exam->questions()->sum("score");
        $noOfBranch = Input::get("noOfBranch");

        //Validation
        $this->validate($request, [
            'title'              => ['required'],
            'type'               => ['required', 'integer', 'between:1,4'],
            'score'              => ['required', 'integer', "between:1,$remainingScore"],
            'noOfBranch'         => ['required', 'integer', 'min:1'],
            'noOfBranchRequired' => ($noOfBranch >= 1)? "required|integer|min:1|between:1,$noOfBranch":"",
        ], [
            'title.required'              => 'يرجى ملئ عنوان السؤال.',
            'type.required'               => 'يرجى أختيار نوع السؤال.',
            'type.integer'                => 'نوع السؤال غير مقبولة.',
            'type.between'                => 'نوع السؤال من 1 الى 4.',
            'score.required'              => 'يرجى وضع درجة السؤال',
            'score.integer'               => 'درجة السؤال غير مقبولة.',
            'score.between'               => 'درجة السؤال اكبر من صفر واقل من درجة الامتحان المتبقية.',
            'noOfBranch.required'         => 'يرجى ذكر عدد النقاط.',
            'noOfBranch.integer'          => 'عدد النقاط غير مقبول.',
            'noOfBranch.min'              => 'يجب ان يحتوي السؤال على نقطة واحدة على الاقل.',
            'noOfBranchRequired.required' => 'يرجى ذكر عدد النقاط المطلوبة.',
            'noOfBranchRequired.integer'  => 'عدد النقاط المطلوبة غير مقبول.',
            'noOfBranchRequired.min'      => 'يجب ان يحتوي السؤال على نقطة واحدة مطلوبة على الاقل.',
            'noOfBranchRequired.between'  => 'يجب ان تكون عدد النقاط المطلوبة اقل من او تساوي عدد النقاط.',
        ]);

        //Transaction
        $exception = DB::transaction(function () use ($exam, &$question){
            //Store question
            $question = new Question();
            $question->exam_id = $exam->id;
            $question->title = Input::get("title");
            $question->type = Input::get("type");
            $question->score = Input::get("score");
            $question->no_of_branch = Input::get("noOfBranch");
            $question->no_of_branch_req = Input::get("noOfBranchRequired");
            $question->correction = QuestionCorrectionState::UNCORRECTED;
            $question->save();

            //Store event log
            $target = $question->id;
            $type = EventLogType::QUESTION;
            $event = "اضافة السؤال: " . $question->title . "الى امتحان " . $exam->title;
            EventLog::create($target, $type, $event);
        });

        if (is_null($exception))
            return redirect("control-panel/questions/$question->id")->with([
                "CreateQuestionMessage" => "تمت اضافة السؤال بنجاح."
            ]);
        else
            return redirect("control-panel/questions/create?exam=$exam->id")->with([
                "CreateQuestionMessage" => "لم يتم اضافة السؤال بنجاح."
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        Auth::check();
        ExamController::watchExam($question->exam);
        return view("ControlPanel.question.show")->with([
            "currentQuestion" => $question
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        Auth::check();
        $exam = $question->exam;
        ExamController::watchExam($exam);
        return view("ControlPanel.question.edit")->with([
            "question" => $question
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Question $question
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Question $question)
    {
        Auth::check();
        $exam = $question->exam;
        ExamController::watchExam($exam);

        if ($exam->state == ExamState::OPEN)
            return redirect("control-panel/questions/$question->id/edit")->with([
                "UpdateQuestionMessage" => "لا يمكنك تعديل السؤال الحالي لان الامتحان التابع له هذا السؤال مفتوح."
            ]);

        //Validation
        $remainingScore = $exam->fake_score - $exam->questions()->sum("score") + $question->score;
        $noOfBranch = ($exam->state==ExamState::CLOSE)?Input::get("noOfBranch"):$question->no_of_branch;
        $this->validate($request, [
            'title'              => ['required'],
            'score'              => ['required', 'integer', "between:1,$remainingScore"],
            ($exam->state==ExamState::CLOSE)?'noOfBranch':'' => ($exam->state==ExamState::CLOSE)?"required|integer|min:1":"",
            'noOfBranchRequired' => ($noOfBranch >= 1)? "required|integer|min:1|between:1,$noOfBranch":"",
        ], [
            'title.required'              => 'يرجى ملئ عنوان السؤال.',
            'score.required'              => 'يرجى وضع درجة السؤال',
            'score.integer'               => 'درجة السؤال غير مقبولة.',
            'score.between'               => 'درجة السؤال اكبر من صفر واقل من درجة الامتحان المتبقية.',
            ($exam->state==ExamState::CLOSE)?'noOfBranch.required':'' => ($exam->state==ExamState::CLOSE)?'يرجى ذكر عدد النقاط.':'',
            ($exam->state==ExamState::CLOSE)?'noOfBranch.integer':'' => ($exam->state==ExamState::CLOSE)?'عدد النقاط غير مقبول.':'',
            ($exam->state==ExamState::CLOSE)?'noOfBranch.min':'' => ($exam->state==ExamState::CLOSE)?'يجب ان يحتوي السؤال على نقطة واحدة على الاقل.':'',
            'noOfBranchRequired.required' => 'يرجى ذكر عدد النقاط المطلوبة.',
            'noOfBranchRequired.integer'  => 'عدد النقاط المطلوبة غير مقبول.',
            'noOfBranchRequired.min'      => 'يجب ان يحتوي السؤال على نقطة واحدة مطلوبة على الاقل.',
            'noOfBranchRequired.between'  => 'يجب ان تكون عدد النقاط المطلوبة اقل من او تساوي عدد النقاط.',
        ]);

        //Transaction
        $exception = DB::transaction(function () use ($question, $exam) {
            //Update question
            $question->title = Input::get("title");
            $question->score = Input::get("score");
            $question->no_of_branch = ($exam->state==ExamState::CLOSE)?Input::get("noOfBranch"):$question->no_of_branch;
            $question->no_of_branch_req = Input::get("noOfBranchRequired");

            //Re-corrected the question
            if (($exam->state == ExamState::END) && (Input::get("reCorrectQuestion")))
            {
                //Update question
                $question->correction = QuestionCorrectionState::UNCORRECTED;

                //Re-corrected the answers
                foreach ($question->branches as $branch)
                    $branch->answers()
                        ->where("correction", AnswerCorrectionState::CORRECTED)
                        ->update(array('correction' => AnswerCorrectionState::UNCORRECTED));

            }
            $question->save();

            //Update branches score
            $question->branches()
                ->update(array('score' => ($question->score / $question->no_of_branch_req)));

            //Store event log
            $target = $question->id;
            $type = EventLogType::QUESTION;
            if (($exam->state == ExamState::END) && Input::get("reCorrectQuestion"))
                $event = "تعديل السؤال: " . $question->title . "في امتحان " . $exam->title . "و هذا الامتحان منتهي، مع اعادة تصحيح السؤال";
            elseif ($exam->state == ExamState::END)
                $event = "تعديل السؤال: " . $question->title . "في امتحان " . $exam->title . "و هذا الامتحان منتهي";
            else
                $event = "تعديل السؤال: " . $question->title . "في امتحان " . $exam->title . "و هذا الامتحان مغلق";
            EventLog::create($target, $type, $event);
        });

        if (is_null($exception))
            return redirect("control-panel/questions/$question->id")->with([
                "UpdateQuestionMessage" => "تم تعديل السؤال الحالي."
            ]);
        else
            return redirect("control-panel/questions/$question->id/edit")->with([
                "UpdateQuestionMessage" => "لم يتم تعديل السؤال الحالي."
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        Auth::check();
        $exam = $question->exam;
        ExamController::watchExam($exam);

        if ($exam->state == ExamState::OPEN)
            return redirect("/control-panel/questions/$question->id")->with([
                "DeleteQuestionMessage" => "لا يمكنك حذف السؤال الحالي لان الامتحان مفتوح حالياً."
            ]);

        //Transaction
        $exception = DB::transaction(function () use ($question, $exam) {
            //Delete answers
            if ($exam->state == ExamState::END)
                foreach ($question->branches as $branch)
                    $branch->answers()->delete();

            //Delete branches
            $question->branches()->delete();

            //Delete question
            $question->delete();

            //Store event log
            $target = $question->id;
            $type = EventLogType::QUESTION;
            $event = "حذف السؤال: " . $question->title . "في امتحان " . $exam->title . "و هذا الامتحان " . ExamState::getState($exam->state);
            EventLog::create($target, $type, $event);
        });

        if (is_null($exception))
            return redirect("/control-panel/exams/$exam->id")->with([
                "DeleteQuestionMessage" => "تم حذف السؤال: " . $question->title
            ]);
        else
            return redirect("/control-panel/questions/$question->id")->with([
                "DeleteQuestionMessage" => "لم يتم حذف السؤال."
            ]);
    }
}
