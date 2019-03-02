<?php

namespace App\Http\Controllers\ControlPanel;

use App\Enums\EventLogType;
use App\Enums\ExamState;
use App\Enums\QuestionType;
use App\Models\Answer;
use App\Models\Branch;
use App\Models\EventLog;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
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
        $exam = $question->exam;
        ExamController::watchExam($exam);

        //Number of branches is overflow
        if ($question->branches()->count() == $question->no_of_branch)
            return redirect("control-panel/branches/create?question=$question->id")->with([
                "CreateBranchMessage" => "تحذير، لا يمكنك اضافة نقطة الى السؤال الحالي.",
                "TypeMessage"         => "Error"
            ]);

        //Validation
        switch ($question->type) {
            case QuestionType::TRUE_OR_FALSE:
                $this->validate($request, [
                    'title'         => ['required'],
                    'correctOption' => ['required', Rule::in("صح", "خطأ")]
                ], [
                    'title.required'         => 'يرجى ملئ عنوان(النص) النقطة.',
                    'correctOption.required' => 'يرجى اختيار الاجابة الصحيحة.',
                    'correctOption.in'       => 'الاجابة الصحيحة غير مقبولة.'
                ]);
                $options = array("صح", "خطأ");
                $correctOption = Input::get("correctOption");
                break;

            case QuestionType::SINGLE_CHOICE:
                $this->validate($request, [
                    'title'         => ['required'],
                    'option-1'      => ['required'],
                    'option-2'      => ['required'],
                    'correctOption' => ['required', Rule::in(
                        Input::get("option-1"),
                        Input::get("option-2"),
                        (!is_null(Input::get("option-3"))) ? Input::get("option-3") : "",
                        (!is_null(Input::get("option-4"))) ? Input::get("option-4") : "")]
                ], [
                    'title.required'        => 'يرجى ملئ عنوان(النص) النقطة.',
                    'option-1.required'     => 'يرجى ملئ الاختيار الاول.',
                    'option-2.required'     => 'يرجى ملئ الاختيار الثاني.',
                    'correctOption.required' => 'يرجى اختيار الاجابة الصحيحة.',
                    'correctOption.in'       => 'الاجابة الصحيحة غير مقبولة.'
                ]);
                $options = array(
                    Input::get("option-1"),
                    Input::get("option-2")
                );
                if (!is_null(Input::get("option-3")))
                    array_push($options, Input::get("option-3"));
                if (!is_null(Input::get("option-4")))
                    array_push($options, Input::get("option-4"));
                $correctOption = Input::get("correctOption");
                break;

            case QuestionType::FILL_BLANK:
                $this->validate($request, [
                    'title' => ['required']
                ], [
                    'title.required' => 'يرجى ملئ عنوان(النص) النقطة.'
                ]);
                $options = null;
                $correctOption = Input::get("correctOption", null);
                break;

            case QuestionType::EXPLAIN:
                $this->validate($request, [
                    'title' => ['required']
                ], [
                    'title.required' => 'يرجى ملئ عنوان(النص) النقطة.'
                ]);
                $options = null;
                $correctOption = Input::get("correctOption", null);
                break;

            default:
                $options = null;
                $correctOption = null;
        }

        //Transaction
        $exception = DB::transaction(function () use ($exam, $question, $options, $correctOption) {
            //Store branch
            $branch = new Branch();
            $branch->question_id = $question->id;
            $branch->title = Input::get("title");
            $branch->options = (!is_null($options)) ? json_encode($options, JSON_UNESCAPED_UNICODE) : null;
            $branch->correct_option = $correctOption;
            $branch->score = $question->score / $question->no_of_branch_req;
            $branch->save();

            //Store event log
            $target = $branch->id;
            $type = EventLogType::BRANCH;
            $event = "اضافة نقطة الى السؤال: " . $question->title . "في الامتحان " . $exam->title;
            EventLog::create($target, $type, $event);
        });

        if (is_null($exception)) {
            if ($question->branches()->count() == $question->no_of_branch)
                return redirect("control-panel/questions/$question->id")->with([
                    "CreateBranchMessage" => "تمت اضافة جميع النقاط الى السؤال الحالي بنجاح."
                ]);
            else
                return redirect("control-panel/branches/create?question=$question->id")->with([
                    "CreateBranchMessage" => "تمت اضافة النقطة الى السؤال الحالي بنجاح."
                ]);
        }
        else
            return redirect("control-panel/branches/create?question=$question->id")->with([
                "CreateBranchMessage" => "لم يتم اضافة النقطة الى السؤال بنجاح.",
                "TypeMessage" => "Error"
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Branch $branch
     * @return \Illuminate\Http\Response
     */
    public function show(Branch $branch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Branch $branch
     * @return \Illuminate\Http\Response
     */
    public function edit(Branch $branch)
    {
        Auth::check();
        $exam = $branch->question->exam;
        ExamController::watchExam($exam);
        return view("ControlPanel.branch.edit")->with([
            "branch" => $branch
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Branch $branch
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Branch $branch)
    {
        Auth::check();
        $exam = $branch->question->exam;
        ExamController::watchExam($exam);
        $question = $branch->question;

        if ($exam->state == ExamState::OPEN)
            return redirect("control-panel/questions/$question->id")->with([
                "UpdateBranchMessage" => "لا يمكنك تعديل اي نقطة لان الامتحان مفتوح"
            ]);

        //Validation
        switch ($question->type) {
            case QuestionType::TRUE_OR_FALSE:
                $this->validate($request, [
                    'title'         => ['required'],
                    'correctOption' => ['required', Rule::in("صح", "خطأ")]
                ], [
                    'title.required'         => 'يرجى ملئ عنوان(النص) النقطة.',
                    'correctOption.required' => 'يرجى اختيار الاجابة الصحيحة.',
                    'correctOption.in'       => 'الاجابة الصحيحة غير مقبولة.'
                ]);
                $options = array("صح", "خطأ");
                $correctOption = Input::get("correctOption");
                break;

            case QuestionType::SINGLE_CHOICE:
                $this->validate($request, [
                    'title'    => ['required'],
                    'option-1' => ['required'],
                    'option-2' => ['required'],
                    'correctOption' => ['required', Rule::in(
                        Input::get("option-1"),
                        Input::get("option-2"),
                        (!is_null(Input::get("option-3"))) ? Input::get("option-3") : "",
                        (!is_null(Input::get("option-4"))) ? Input::get("option-4") : "")]
                ], [
                    'title.required' => 'يرجى ملئ عنوان(النص) النقطة.',
                    'option-1.required' => 'يرجى ملئ الاختيار الاول.',
                    'option-2.required' => 'يرجى ملئ الاختيار الثاني.',
                    'correctOption.required' => 'يرجى اختيار الاجابة الصحيحة.',
                    'correctOption.in' => 'الاجابة الصحيحة غير مقبولة.'
                ]);
                $options = array(
                    Input::get("option-1"),
                    Input::get("option-2")
                );
                if (!is_null(Input::get("option-3")))
                    array_push($options, Input::get("option-3"));
                if (!is_null(Input::get("option-4")))
                    array_push($options, Input::get("option-4"));
                $correctOption = Input::get("correctOption");
                break;

            case QuestionType::FILL_BLANK:
                $this->validate($request, [
                    'title' => ['required']
                ], [
                    'title.required' => 'يرجى ملئ عنوان(النص) النقطة.'
                ]);
                $options = null;
                $correctOption = Input::get("correctOption", null);
                break;

            case QuestionType::EXPLAIN:
                $this->validate($request, [
                    'title' => ['required']
                ], [
                    'title.required' => 'يرجى ملئ عنوان(النص) النقطة.'
                ]);
                $options = null;
                $correctOption = Input::get("correctOption", null);
                break;

            default:
                $options = null;
                $correctOption = null;
        }

        //Transaction
        $exception = DB::transaction(function () use ($exam, $question, $branch, $options, $correctOption) {
            //Update branch
            $branch->title = Input::get("title");
            $branch->options = (!is_null($options)) ? json_encode($options, JSON_UNESCAPED_UNICODE) : null;
            $branch->correct_option = $correctOption;
            $branch->save();

            //Update Answers
            if (($exam->state == ExamState::END) && Input::get("reCorrectOption")) {
                //Update Answers
                $branch->answers()->update(array("re_correct" => 1));

                //Store event log
                $target = $branch->id;
                $type = EventLogType::BRANCH;
                $event = "تم اعتبار اجابة الطلبة صحيحة في النقطة: " . $branch->title . " في السؤال: " . $question->title . " في الامتحان: " . $exam->title;
                EventLog::create($target, $type, $event);
            }

            //Store event log
            $target = $branch->id;
            $type = EventLogType::BRANCH;
            $event = "تعديل النقطة: " . $branch->title . " في السؤال: " . $question->title . " في الامتحان: " . $exam->title;
            EventLog::create($target, $type, $event);
        });

        if (is_null($exception))
            return redirect("control-panel/questions/$question->id")->with([
                "UpdateBranchMessage" => "تم تعديل النقطة: " . $branch->title . " بنجاح"
            ]);
        else
            return redirect("control-panel/branches/$question->id/edit")->with([
                "UpdateBranchMessage" => "لم يتم تعديل النقطة بنجاح."
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Branch $branch
     * @return \Illuminate\Http\Response
     */
    public function destroy(Branch $branch)
    {
        Auth::check();
        $exam = $branch->question->exam;
        ExamController::watchExam($exam);
        $question = $branch->question;

        if ($exam->state == ExamState::OPEN)
            return redirect("control-panel/questions/$question->id")->with([
                "DeleteBranchMessage" => "لا يمكنك حذف اي نقطة لان الامتحان مفتوح",
                "TypeMessage" => "Error"
            ]);

        if ($exam->state == ExamState::END)
            return redirect("control-panel/questions/$question->id")->with([
                "DeleteBranchMessage" => "لا يمكنك حذف اي نقطة لان الامتحان منتهي",
                "TypeMessage" => "Error"
            ]);

        //Delete Branch when current exam state is closed
        $exception = DB::transaction(function () use ($exam, $question, $branch) {
            //Delete
            $branch->delete();

            //Store event log
            $target = $branch->id;
            $type = EventLogType::BRANCH;
            $event = "حذف النقطة: " . $branch->title . "في السؤال: " . $question->title . "في الامتحان " . $exam->title;
            EventLog::create($target, $type, $event);
        });

        if (is_null($exception))
            return redirect("control-panel/questions/$question->id")->with([
                "DeleteBranchMessage" => "تم حذف النقطة: " . $branch->title . " بنجاح"
            ]);
        else
            return redirect("control-panel/branches/$question->id")->with([
                "DeleteBranchMessage" => "لم يتم حذف النقطة بنجاح.",
                "TypeMessage" => "Error"
            ]);
    }
}
