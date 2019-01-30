<?php

namespace App\Http\Controllers\ControlPanel;

use App\Enums\EventLogType;
use App\Models\EventLog;
use App\Models\Exam;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use phpDocumentor\Reflection\Types\Self_;

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
        return view("ControlPanel.question.create")->with([
            "exam" => self::getCurrentExam()
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
        $exam = self::getCurrentExam();
        $remainingScore = $exam->fake_mark - $exam->questions()->sum("score");
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
        $question->title = Input::get("title");
        $question->type = Input::get("type");
        $question->score = Input::get("score");
        $question->no_of_branch = Input::get("noOfBranch");
        $question->no_of_beanch_req = Input::get("noOfBranchRequired");
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

        return redirect("control-panel/branches/create")->with([
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question)
    {
        //
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


    /**
     * Get current exam if exists
     * Or redirect to index page of exams
     */
    private static function getCurrentExam()
    {
        if (session()->has("PreviousRequest") && session()->has("CurrentExam") && session()->get("PreviousRequest") == "control-panel/exams/" . session()->get("CurrentExam"))
            return Exam::findOrFail(session()->get("CurrentExam"));
        else
            return abort(302, '', ['Location' => "/control-panel/exams"]);
    }
}
