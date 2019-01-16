<?php

namespace App\Http\Controllers\ControlPanel;

use App\Enums\AccountType;
use App\Enums\CourseState;
use App\Models\Course;
use App\Models\Exam;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rule;

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
            'mark'   => ['required'],
            'date'   => ['required', 'date', 'after_or_equal:today']
        ], [
            'course.required'      => 'يرجى اختيار المادة',
            'course.in'            => 'المادة المختارة غير مقبولة.',
            'type.required'        => 'يرجى اختيار نوع الامتحان.',
            'type.integer'         => 'نوع الامتحان غير مقبولة.',
            'type.between'         => 'نوع الامتحان من 1 الى 4.',
            'type.not_in'          => 'تم انشاء هذا النموذج الامتحاني مسبقا، لهذه المادة في هذا النوع من الامتحان.',
            'title.required'       => 'يرجى ملىء عنوان الامتحان.',
            'date.required'        => 'يرجى ملىء تاريخ الامتحان.',
            'date.date'            => 'تاريخ الامتحان غير مقبولة.',
            'date.after_or_equal'  => 'تاريخ الامتحان يجب ان يكون من اليوم فصاعدا.'
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
