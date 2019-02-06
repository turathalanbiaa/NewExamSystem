<?php

namespace App\Http\Controllers\ControlPanel;

use App\Enums\EventLogType;
use App\Enums\QuestionType;
use App\Models\Branch;
use App\Models\EventLog;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rule;

class BranchController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Auth::check();
        $question = Question::findOrFail(Input::get("question"));
        ExamController::watchExam($question->exam);
        return view("ControlPanel.branch.create")->with([
            "question" => $question
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
        $question = Question::findOrFail(Input::get("question"));
        ExamController::watchExam($question->exam);
        if ($question->branches->count() == $question->no_of_branch)
            return redirect("control-panel/branches/create?question=$question->id")->with([
                "CreateBranchMessage" => "تحذير، لا يمكنك اضافة نقطة الى السؤال الحالي.",
                "TypeMessage" => "Error"
            ]);

        switch ($question->type)
        {
            case QuestionType::TRUE_OR_FALSE:
                $this->validate($request, [
                    'title'         => ['required'],
                    'correctOption' => ['required', Rule::in("صح", "خطأ")]
                ], [
                    'title.required'         => 'يرجى ملئ عنوان(النص) النقطة.',
                    'correctOption.required' => 'يرجى اختيار الاجابة الصحيحة.',
                    'correctOption.in'       => 'الاجابة الصحيحة غير مقبولة.'
                ]);
                $options = array("صح","خطأ");
                $correctOption = Input::get("correctOption");
                break;

            case QuestionType::SINGLE_CHOICE:
                $this->validate($request, [
                    'title'         => ['required'],
                    'option-1'      => ['required'],
                    'option-2'      => ['required'],
                    'option-3'      => ['required'],
                    'correctOption' => ['required', Rule::in("option-1", "option-2", "option-3", (!is_null(Input::get("option-4")))?"option-4":"")]
                ], [
                    'title.required'         => 'يرجى ملئ عنوان(النص) النقطة.',
                    'option-1.required'      => 'يرجى ملئ الاختيار الاول.',
                    'option-2.required'      => 'يرجى ملئ الاختيار الثاني.',
                    'option-3.required'      => 'يرجى ملئ الاختيار الثالث.',
                    'correctOption.required' => 'يرجى اختيار الاجابة الصحيحة.',
                    'correctOption.in'       => 'الاجابة الصحيحة غير مقبولة.'
                ]);
                $options = array(
                    Input::get("option-1"),
                    Input::get("option-2"),
                    Input::get("option-3")
                );
                if (!is_null(Input::get("option-4")))
                    array_push($options, Input::get("option-4"));
                $correctOption = Input::get(Input::get("correctOption"));
                break;

            case QuestionType::FILL_BLANK:
                $this->validate($request, [
                    'title'         => ['required']
                ], [
                    'title.required'         => 'يرجى ملئ عنوان(النص) النقطة.'
                ]);
                $options = null;
                $correctOption = Input::get("correctOption", null);
                break;

            case QuestionType::EXPLAIN:
                $this->validate($request, [
                    'title'         => ['required']
                ], [
                    'title.required'         => 'يرجى ملئ عنوان(النص) النقطة.'
                ]);
                $options = null;
                $correctOption = Input::get("correctOption", null);
                break;

            default:
                $options = null;
                $correctOption = null;
        }

        $branch = new Branch();
        $branch->question_id = $question->id;
        $branch->title = Input::get("title");
        $branch->options = (!is_null($options))?json_encode($options, JSON_UNESCAPED_UNICODE):null;
        $branch->correct_option = $correctOption;
        $branch->score = $question->score / $question->no_of_branch_req;
        $branch->re_correct = 0; //Default 0
        $success = $branch->save();

        if (!$success)
            return redirect("control-panel/branches/create?question=$question->id")->with([
                "CreateBranchMessage" => "لم يتم اضافة النقطة الى السؤال بنجاح.",
                "TypeMessage" => "Error"
            ]);

        //Store event log
        $target = $branch->id;
        $type = EventLogType::BRANCH;
        $event = "اضافة نقطة الى السؤال - " . $question->title;
        EventLog::create($target, $type, $event);

        if (($question->branches()->count()+1) == $question->no_of_branch)
            return redirect("control-panel/questions/$question->id")->with([
                "CreateBranchMessage" => "تمت اضافة جميع النقاط الى السؤال الحالي بنجاح."
            ]);
        else
            return redirect("control-panel/branches/create?question=$question->id")->with([
                "CreateBranchMessage" => "تمت اضافة النقطة الى السؤال الحالي بنجاح."
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function show(Branch $branch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function edit(Branch $branch)
    {
        dd("Edit");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Branch $branch)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function destroy(Branch $branch)
    {
        //
    }
}
