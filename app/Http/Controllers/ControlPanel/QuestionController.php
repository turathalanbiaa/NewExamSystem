<?php

namespace App\Http\Controllers\ControlPanel;

use App\Enums\EventLogType;
use App\Enums\ExamState;
use App\Models\EventLog;
use App\Models\Exam;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

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

        $question = new Question();
        $question->exam_id = $exam->id;
        $question->title = Input::get("title");
        $question->type = Input::get("type");
        $question->score = Input::get("score");
        $question->no_of_branch = Input::get("noOfBranch");
        $question->no_of_branch_req = Input::get("noOfBranchRequired");
        $success = $question->save();

        if (!$success)
            return redirect("control-panel/questions/create")->with([
                "CreateQuestionMessage" => "لم يتم اضافة السؤال بنجاح."
            ]);

        //Keep event log
        $target = $question->id;
        $type = EventLogType::QUESTION;
        $event = "اضافة سؤال لامتحان - " . $exam->title;
        EventLog::create($target, $type, $event);

        return redirect("control-panel/questions/$question->id")->with([
            "CreateQuestionMessage" => "تمت اضافة السؤال بنجاح."
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
            "exam"     => $exam,
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
        $remainingScore = $exam->fake_score - $exam->questions()->sum("score") + $question->score;
        $noOfBranch = Input::get("noOfBranch");

        if ($exam->state == ExamState::CLOSE)
        {
            $this->validate($request, [
                'title'              => ['required'],
                'score'              => ['required', 'integer', "between:1,$remainingScore"],
                'noOfBranch'         => ['required', 'integer', 'min:1'],
                'noOfBranchRequired' => ($noOfBranch >= 1)? "required|integer|min:1|between:1,$noOfBranch":"",
            ], [
                'title.required'              => 'يرجى ملئ عنوان السؤال.',
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


        }
        elseif ($exam->state == ExamState::OPEN)
        {

        }
        else
        {

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        //
    }
}
