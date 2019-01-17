<?php

namespace App\Http\Controllers\ControlPanel;

use App\Enums\AccountType;
use App\Enums\CourseState;
use App\Enums\EventLogType;
use App\Enums\ExamState;
use App\Enums\ExamType;
use App\Models\Course;
use App\Models\EventLog;
use App\Models\Exam;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\In;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (session("EXAM_SYSTEM_ACCOUNT_TYPE") == AccountType::MANAGER)
        {
            $courses = Course::all();
            $exams = Exam::all();
        }
        else
        {
            $courses = Course::where("lecturer_id", session("EXAM_SYSTEM_ACCOUNT_ID"))->get();
            $exams = Exam::whereIn("course_id", $courses->pluck('id')->toArray())->get();
        }

        return view("ControlPanel.exam.index")->with([
            "courses" => $courses,
            "exams"   => $exams
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (session()->get("EXAM_SYSTEM_ACCOUNT_TYPE") == AccountType::LECTURER)
            $courses = Course::where("lecturer_id", session()->get("EXAM_SYSTEM_ACCOUNT_ID"))
                ->where("state", CourseState::OPEN)
                ->get();
        else
            $courses = Course::where("state", CourseState::OPEN)
                ->get();

        return view("ControlPanel.exam.create")->with([
            "courses" => $courses
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
        $this->validate($request, [
            'course' => ['required', Rule::in(
                session()->get("EXAM_SYSTEM_ACCOUNT_TYPE") == AccountType::LECTURER ?
                    Course::where("lecturer_id", session()->get("EXAM_SYSTEM_ACCOUNT_ID"))
                        ->where("state", CourseState::OPEN)
                        ->pluck("id")
                        ->toArray() :
                    Course::where("state", CourseState::OPEN)
                            ->pluck("id")
                            ->toArray()
            )],
            'type'   => ['required', 'integer', 'between:1,4', Rule::notIn(
                Exam::where("course_id", Input::get("course"))
                    ->pluck("type")
                    ->toArray()
            )],
            'title'  => ['required'],
            'mark'   => ['required', 'integer', ((Input::get("type") == ExamType::FIRST_MONTH) || (Input::get("type") == ExamType::SECOND_MONTH)) ? 'between:1,25' : 'between:1,60'],
            'date'   => ['required', 'date', 'after_or_equal:today']
        ], [
            'course.required'      => 'يرجى اختيار المادة',
            'course.in'            => 'المادة المختارة غير مقبولة.',
            'type.required'        => 'يرجى اختيار نوع الامتحان.',
            'type.integer'         => 'نوع الامتحان غير مقبولة.',
            'type.between'         => 'نوع الامتحان من 1 الى 4.',
            'type.not_in'          => 'تم انشاء هذا النموذج الامتحاني مسبقا، لهذه المادة في هذا النوع من الامتحان.',
            'title.required'       => 'يرجى ملىء عنوان الامتحان.',
            'mark.required'        => 'يرجى ملىء درجة الامتحان.',
            'mark.integer'         => 'درجة الامتحان غير مقبولة.',
            'mark.between'         => ((Input::get("type") == ExamType::FIRST_MONTH) || (Input::get("type") == ExamType::SECOND_MONTH)) ? 'درجة الامتحان من 25.' : 'درجة الامتحان من 60.',
            'date.required'        => 'يرجى ملىء تاريخ الامتحان.',
            'date.date'            => 'تاريخ الامتحان غير مقبولة.',
            'date.after_or_equal'  => 'تاريخ الامتحان يجب ان يكون من اليوم فصاعدا.'
        ]);

        //Generate mark for exam.
        $mark = Input::get("mark");
        if (Input::get("type") == ExamType::SECOND_MONTH)
        {
            $firstMonth = Exam::where("course_id", Input::get("course"))
                ->where("type", ExamType::FIRST_MONTH)
                ->first();

            if (!$firstMonth)
                return redirect("/control-panel/exams/create")->with([
                    "CreateExamMessage" => "لا يمكنك انشاء النموذج الامتحاني للشهر الثاني الا بعد انشاء النموذج الامتحاني للشهر الاول."
                ]);

            if ($firstMonth->real_mark == 25)
                return redirect("/control-panel/exams/create")->with([
                    "CreateExamMessage" => "لا يمكنك انشاء النموذج الامتحاني للشهر الثاني لان درجة امتحان الشهر الاول 25 درجة."
                ]);

            $mark = 25 - $firstMonth->real_mark;
        }

        $exam = new Exam();
        $exam->title = Input::get("title");
        $exam->course_id = Input::get("course");
        $exam->type = Input::get("type");
        $exam->state = ExamState::CLOSE;
        $exam->real_mark = $mark;
        $exam->fake_mark = 100; //Default Value 100
        $exam->curve = 0;       //Default Value 0
        $exam->date = Input::get("date");
        $success = $exam->save();

        if (!$success)
            return redirect("/control-panel/exams/create")->with([
                "CreateExamMessage" => "لم يتم انشاء النموذج الامتحاني."
            ]);

        $target = $exam->id;
        $type = EventLogType::EXAM;
        $event = "انشاء نموذج امتحاني " . $exam->title;
        EventLog::create($target, $type, $event);

        return redirect("/control-panel/exams")->with([
            "CreateExamMessage" => "تم انشاء النموذج الامتحاني " . $exam->title . "."
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function show(Exam $exam)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function edit(Exam $exam)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Exam $exam)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function destroy(Exam $exam)
    {
        //
    }
}
