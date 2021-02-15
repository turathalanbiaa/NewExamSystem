<?php

namespace App\Http\Controllers\ControlPanel;

use App\Enums\AccountType;
use App\Enums\CourseState;
use App\Enums\EventLogType;
use App\Enums\ExamState;
use App\Enums\ExamStudentState;
use App\Enums\ExamType;
use App\Enums\QuestionCorrectionState;
use App\Models\Answer;
use App\Models\Course;
use App\Models\EventLog;
use App\Models\Exam;
use App\Models\ExamStudent;
use App\Models\Student;
use App\Models\StudentDocument;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
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
        Auth::check();
        return view("ControlPanel.exam.index")->with([
            "courses" => CourseController::getCourses()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Auth::check();
        return view("ControlPanel.exam.create")->with([
            "courses" => CourseController::getCoursesOpen()
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
        //Validation
        $this->validate($request, [
            'course' => ['required', Rule::in(CourseController::getCoursesOpen()->pluck("id")->toArray())],
            'type'   => ['required', 'integer', 'between:1,4', Rule::notIn(self::getExamTypes(Input::get("course")))],
            'title'  => ['required'],
            'score'  => ['required', 'integer', ((Input::get("type") == ExamType::FIRST_MONTH) || (Input::get("type") == ExamType::SECOND_MONTH)) ? 'between:1,25' : 'between:1,60'],
            'date'   => ['required', 'date', 'after_or_equal:today']
        ], [
            'course.required'      => 'يرجى اختيار المادة',
            'course.in'            => 'المادة المختارة غير مقبولة.',
            'type.required'        => 'يرجى اختيار نوع الامتحان.',
            'type.integer'         => 'نوع الامتحان غير مقبولة.',
            'type.between'         => 'نوع الامتحان من 1 الى 4.',
            'type.not_in'          => 'تم انشاء هذا النموذج الامتحاني مسبقا، لهذه المادة في هذا النوع من الامتحان.',
            'title.required'       => 'يرجى ملىء عنوان الامتحان.',
            'score.required'       => 'يرجى ملىء درجة الامتحان.',
            'score.integer'        => 'درجة الامتحان غير مقبولة.',
            'score.between'        => ((Input::get("type") == ExamType::FIRST_MONTH) || (Input::get("type") == ExamType::SECOND_MONTH)) ? 'درجة الامتحان من 25.' : 'درجة الامتحان من 60.',
            'date.required'        => 'يرجى ملىء تاريخ الامتحان.',
            'date.date'            => 'تاريخ الامتحان غير مقبولة.',
            'date.after_or_equal'  => 'تاريخ الامتحان يجب ان يكون من اليوم فصاعدا.'
        ]);

        //Generate score for exam
        $score = Input::get("score");
        if (Input::get("type") == ExamType::SECOND_MONTH)
        {
            $firstMonthExam = Exam::where("course_id", Input::get("course"))
                ->where("type", ExamType::FIRST_MONTH)
                ->first();

            if (!$firstMonthExam)
                return redirect("/control-panel/exams/create")->with([
                    "CreateExamMessage" => "لا يمكنك انشاء النموذج الامتحاني لشهر الثاني الا بعد انشاء النموذج الامتحاني لشهر الاول"
                ]);

            if ($firstMonthExam->real_score == 25)
                return redirect("/control-panel/exams/create")->with([
                    "CreateExamMessage" => "لا يمكنك انشاء النموذج الامتحاني لشهر الثاني لان درجة امتحان الشهر الاول 25 درجة"
                ]);

            $score = 25 - $firstMonthExam->real_score;
        }

        if (Input::get("type") == ExamType::FINAL_SECOND_ROLE)
        {
            $finalFirstRoleExam = Exam::where("course_id", Input::get("course"))
                ->where("type", ExamType::FINAL_FIRST_ROLE)
                ->first();

            if (!$finalFirstRoleExam)
                return redirect("/control-panel/exams/create")->with([
                    "CreateExamMessage" => "لا يمكنك انشاء النموذج الامتحاني لنهائي الدور الثاني الا بعد انشاء النموذج الامتحاني لنهائي الدور الاول"
                ]);
        }

        //Transaction
        $exception = DB::transaction(function () use ($score, &$exam) {
            //Store exam
            $exam = new Exam();
            $exam->title = Input::get("title");
            $exam->course_id = Input::get("course");
            $exam->type = Input::get("type");
            $exam->state = ExamState::CLOSE;
            $exam->real_score = $score;
            $exam->fake_score = 100; //Default Value 100
            $exam->curve = 0;       //Default Value 0
            $exam->date = Input::get("date");
            $exam->save();

            //Store event log
            $target = $exam->id;
            $type = EventLogType::EXAM;
            $event = "انشاء النموذج الامتحاني " . $exam->title;
            EventLog::create($target, $type, $event);
        });

        if (is_null($exception))
            return redirect("/control-panel/exams/create")->with([
                "CreateExamMessage" => "تم انشاء النموذج الامتحاني " . $exam->title
            ]);
        else
            return redirect("/control-panel/exams/create")->with([
                "CreateExamMessage" => "لم يتم انشاء النموذج الامتحاني",
                "TypeMessage" => "Error"
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Exam $exam
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Exam $exam)
    {
        Auth::check();
        self::watchExam($exam);
        return view("ControlPanel.exam.show")->with([
            "exam" => $exam
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function edit(Exam $exam)
    {
        Auth::check();
        self::watchExam($exam);
        return view("ControlPanel.exam.edit")->with([
            "exam" => $exam
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Exam $exam
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Exam $exam)
    {
        Auth::check();
        self::watchExam($exam);

        //Update state for exam
        if (Input::get("state"))
        {
            //change exam state
            switch (Input::get("state"))
            {
                case "open":
                    if (!self::complete($exam))
                        return redirect("/control-panel/exams")->with([
                            "UpdateExamStateMessage" => "لا يمكن فتح النموذج الامتحاني " . $exam->title . " لان النموذج الامتحاني غير جاهز",
                            "TypeMessage" => "Error"
                        ]);
                    $exam->state = ExamState::OPEN;
                    $event = "فتح النموذج الامتحاني " . $exam->title;
                    break;
                case "end":
                    $exam->state = ExamState::END;
                    $event = "انهاء النموذج الامتحاني " . $exam->title;
                    break;
                case "reopen":
                    if (!self::complete($exam))
                        return redirect("/control-panel/exams")->with([
                            "UpdateExamStateMessage" => "لا يمكن اعادة فتح النموذج الامتحاني " . $exam->title . " لان النموذج الامتحاني غير جاهز",
                            "TypeMessage" => "Error"
                        ]);
                    $exam->state = ExamState::OPEN;
                    $event = "اعادة فتح النموذج الامتحاني " . $exam->title;
                    break;
                default:
                    $exam->state = ExamState::CLOSE;
                    $event = "غلق النموذج الامتحاني " . $exam->title . " بسبب تلاعب المستخدم بالبيانات";
            }

            //Transaction
            $exception = DB::transaction(function () use ($exam, $event){
                //Make questions for the exam are uncorrected
                $exam->questions()
                    ->update(array('correction' => QuestionCorrectionState::UNCORRECTED));

                //Update exam
                $exam->save();


                // finished exam for all student when exam is end
                if ($exam->state == ExamState::END) {
                    $exam_students = ExamStudent::where("exam_id", $exam->id)->get();
                    foreach ($exam_students as $exam_student)
                        $exam_student->update([
                            "state" => ExamStudentState::FINISHED
                        ]);
                }

                // not finished exam for all student when exam is reopen
                if (Input::get("state") == "reopen") {
                    $exam_students = ExamStudent::where("exam_id", $exam->id)->get();
                    foreach ($exam_students as $exam_student)
                        $exam_student->update([
                            "state" => ExamStudentState::NOT_FINISHED
                        ]);
                }

                //Store event log
                $target = $exam->id;
                $type = EventLogType::EXAM;
                EventLog::create($target, $type, $event);
            });

            if (is_null($exception))
                return redirect("/control-panel/exams")->with([
                    "UpdateExamStateMessage" => "تم " . $event
                ]);
            else
                return redirect("/control-panel/exams")->with([
                    "UpdateExamStateMessage" => "لم يتم تغيير حالة النموذج الامتحاني " . $exam->title,
                    "TypeMessage" => "Error"
                ]);
        }

        //Update curve for exam
        if (Input::get("curve"))
        {
            if (($exam->type == ExamType::FIRST_MONTH) && ($exam->type == ExamType::SECOND_MONTH))
                return redirect("/control-panel/exams/$exam->id")->with([
                    "UpdateExamCurveMessage" => "لا يمكن اضافة كيرف للامتحان الحالي",
                    "TypeMessage" => "Error"
                ]);

            //Transaction
            $exception = DB::transaction(function () use ($exam){
                //Update exam
                $exam->curve = (Input::get("curve") > 10) ? 10:Input::get("curve");
                $exam->save();

                //Store event log
                $target = $exam->id;
                $type = EventLogType::EXAM;
                $event = "اضافة كيرف للنموذج الامتحاني " . $exam->title;
                EventLog::create($target, $type, $event);
            });

            if (is_null($exception))
                return redirect("/control-panel/exams/$exam->id")->with([
                    "UpdateExamCurveMessage" => "تم اضافة الكيرف للامتحان الحالي"
                ]);
            else
                return redirect("/control-panel/exams/$exam->id")->with([
                    "UpdateExamCurveMessage" => "لم يتم اضافة الكيرف للامتحان الحالي",
                    "TypeMessage"       => "Error"
                ]);
        }

        //General update for exam
        if (Input::get("general"))
        {
            //Validation
            $this->validate($request, [
                'title' => ['required'],
                'score' => ['required', 'integer', (($exam->type == ExamType::FIRST_MONTH) || ($exam->type == ExamType::SECOND_MONTH)) ? 'between:1,25' : 'between:1,60'],
                'date'  => ['required', 'date']
            ], [
                'title.required' => 'يرجى ملىء عنوان الامتحان.',
                'score.required' => 'يرجى ملىء درجة الامتحان.',
                'score.integer'  => 'درجة الامتحان غير مقبولة.',
                'score.between'  => (($exam->type == ExamType::FIRST_MONTH) || ($exam->type == ExamType::SECOND_MONTH)) ? 'درجة الامتحان من 25.' : 'درجة الامتحان من 60.',
                'date.required'  => 'يرجى ملىء تاريخ الامتحان.',
                'date.date'      => 'تاريخ الامتحان غير مقبولة.',
            ]);

            //Generate score for exam
            $score = Input::get("score");
            if ($exam->type == ExamType::FIRST_MONTH)
            {
                $secondMonthExam = Exam::where("course_id", $exam->course_id)
                    ->where("type", ExamType::SECOND_MONTH)
                    ->first();

                if (($score == 25) && ($secondMonthExam))
                    redirect("/control-panel/exams/$exam->id/edit")->with([
                        "UpdateExamMessage" => "لا يمكنك وضع (25 درجة) لامتحان الشهر الاول  لهذه المادة لانها تملك امتحان شهر ثاني"
                    ]);

                if ($secondMonthExam)
                {
                    $secondMonthExam->real_score = 25 - $score;
                    $secondMonthExam->save();
                }
            }

            if ($exam->type == ExamType::SECOND_MONTH)
            {
                $firstMonthExam = Exam::where("course_id", $exam->course_id)
                    ->where("type", ExamType::FIRST_MONTH)
                    ->first();

                if ($score == 25)
                    redirect("/control-panel/exams/$exam->id/edit")->with([
                        "UpdateExamMessage" => "لا يمكنك وضع (25 درجة) لامتحان الشهر الثاني"
                    ]);

                $firstMonthExam->real_score = 25 - $score;
                $firstMonthExam->save();
            }

            //Transaction
            $exception = DB::transaction(function () use ($exam, $score){
                //Update exam
                $exam->title = Input::get("title");
                $exam->real_score = $score;
                $exam->date = Input::get("date");
                $exam->save();

                //Store event log
                $target = $exam->id;
                $type = EventLogType::EXAM;
                $event = "تعديل النموذج الامتحاني " . $exam->title;
                EventLog::create($target, $type, $event);
            });

            if (is_null($exception))
                return redirect("/control-panel/exams")->with([
                    "UpdateExamMessage" => "تم تعديل النموذج الامتحاني " . $exam->title
                ]);
            else
                return redirect("/control-panel/exams/$exam->id/edit")->with([
                    "UpdateExamMessage" => "لم يتم نعديل النموذج الامتحاني " . $exam->title
                ]);
        }

        //Otherwise
        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function destroy(Exam $exam)
    {
        Auth::check();
        self::watchExam($exam);
        if ($exam->state == ExamState::OPEN)
            return redirect("/control-panel/exams")->with([
                "DeleteExamMessage" => "لا يمكنك حذف النوذج الامتحاني " . $exam->title . " لان الامتحان جاري",
                "TypeMessage" => "Error"
            ]);

        //Transaction
        $exception = DB::transaction(function () use ($exam) {

            //Delete questions, branches and answers
            foreach ($exam->questions as $question)
            {
                //Delete answers
                foreach ($question->branches as $branch)
                    $branch->answers()->delete();

                //Delete branches
                $question->branches()->delete();

                //Delete question
                $question->delete();
            }

            //Delete the exam from the student document
            // foreach ($exam->studentsEnrolled as $studentEnrolled)
            //     StudentDocument::where('student_id', $studentEnrolled->student_id)
            //         ->where("course_id", $exam->course_id)
            //         ->update(
            //             $exam->type == ExamType::FIRST_MONTH?["first_month_score" => 0]:
            //                 $exam->type == ExamType::SECOND_MONTH?["second_month_score" => 0]:
            //                     $exam->type == ExamType::FINAL_FIRST_ROLE?["final_first_score" => 0]:["final_second_score" => 0]
            //         );

            //Delete students enrolled for the exam
            $exam->studentsEnrolled()->delete();

            //Delete exam
            $exam->delete();

            //Store event log
            $target = $exam->id;
            $type = EventLogType::EXAM;
            $event = "حذف النموذج الامتحاني " . $exam->title;
            EventLog::create($target, $type, $event);
        });

        if (is_null($exception))
            return redirect("/control-panel/exams")->with([
                "DeleteExamMessage" => "تم حذف النموذج الامتحاني  " . $exam->title
            ]);
        else
            return redirect("/control-panel/exams")->with([
                "DeleteExamMessage" => "لم يتم حذف النموذج الامتحاني  " .$exam->title,
                "TypeMessage" => "Error"
            ]);
    }

    /**
     * Get exam types from the specified course
     *
     * @param $course
     * @return mixed
     */
    private static function getExamTypes($course)
    {
        return Exam::where("course_id", $course)
            ->pluck("type")
            ->toArray();
    }

    /**
     * Can watch the specified exam form storage
     *
     * @param $exam
     */
    public static function watchExam($exam)
    {
        if(!in_array($exam->course_id, CourseController::getCoursesOpen()->pluck("id")->toArray()))
            abort(404);
    }

    /**
     * Check the exam questions are created
     *
     * @param $exam
     * @return bool
     */
    private static function complete($exam)
    {
        //Check score
        $score = $exam->questions()->sum("score");
        if ($exam->fake_score != $score)
            return false;

        //Check questions
        foreach ($exam->questions as $question)
            if ($question->no_of_branch != $question->branches()->count())
                return false;

        return true;
    }
}
