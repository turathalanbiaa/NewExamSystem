<?php

namespace App\Http\Controllers\ControlPanel;

use App\Enums\AccountState;
use App\Enums\CourseState;
use App\Enums\EventLogType;
use App\Enums\ExamState;
use App\Enums\ExamType;
use App\Models\Course;
use App\Models\EventLog;
use App\Models\Exam;
use App\Models\Lecturer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courses = Course::all();
        return view("ControlPanel.course.index")->with([
            "courses" => $courses
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $lecturers = Lecturer::all();
        return view("ControlPanel.course.create")->with([
            "lecturers" => $lecturers
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
        $lecturers = Lecturer::where("state", AccountState::OPEN)
            ->pluck("id")
            ->toArray();

        $this->validate($request, [
            'name'     => 'required',
            'level'    => 'required|integer|between:1,7',
            'lecturer' => ['required', Rule::in($lecturers)],
            'state'    => 'required|integer|between:1,2',
            'detail'   => 'required'
        ], [
            'name.required'     => 'اسم المادة فارغ.',
            'level.required'    => 'يجب اختيار المستوى.',
            'level.integer'     => 'يجب اختيارالمستوى بين (1-7).',
            'level.between'     => 'يجب اختيارالمستوى بين (المستوى التمهيدي - المستوى السادس).',
            'lecturer.required' => 'يجب اختيار استاذ المادة.',
            'lecturer.in'       => 'هذا الاستاذ غير موجود.',
            'state.required'    => 'يجب اختيار حالة المادة.',
            'state.integer'     => 'يجب اختيار حالة المادة اما 1 او 2.',
            'state.between'     => 'يجب اختيار حالة المادة اما مفتوحه او مغلقه.',
            'detail.required'   => 'لايوجد تفاصيل حول المادة.'
        ]);

        $course = new Course();
        $course->name = Input::get("name");
        $course->level = Input::get("level");
        $course->lecturer_id = Input::get("lecturer");
        $course->state = Input::get("state");
        $course->detail = Input::get("detail");
        $course->date = date("Y-m-d");
        $success = $course->save();

        if (!$success)
            return redirect("/control-panel/courses/create")->with([
                "CreateCourseMessage" => "لم تتم عملية اضافة المادة بنجاح"
            ]);

        $target = $course->id;
        $type = EventLogType::COURSE;
        $event = "اضافة مادة جديدة";
        EventLog::create($target, $type, $event);

        return redirect("/control-panel/courses/create")->with([
            "CreateCourseMessage" => "تمت عملية اضافة المادة بنجاح"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        return redirect("/control-panel/courses");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        $lecturers = Lecturer::all();
        return view("ControlPanel.course.edit")->with([
            "course"    => $course,
            "lecturers" => $lecturers
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Course $course
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Course $course)
    {
        $this->validate($request, [
            'name'     => 'required',
            'level'    => 'required|integer|between:1,7',
            'lecturer' => ['required', Rule::in(self::getLecturers())],
            'state'    => 'required|integer|between:1,2',
            'detail'   => 'required'
        ], [
            'name.required'     => 'اسم المادة فارغ.',
            'level.required'    => 'يجب اختيار المستوى.',
            'level.integer'     => 'يجب اختيارالمستوى بين (1-7).',
            'level.between'     => 'يجب اختيارالمستوى بين (المستوى التمهيدي - المستوى السادس).',
            'lecturer.required' => 'يجب اختيار استاذ المادة.',
            'lecturer.in'       => 'هذا الاستاذ غير موجود.',
            'state.required'    => 'يجب اختيار حالة المادة.',
            'state.integer'     => 'يجب اختيار حالة المادة اما 1 او 2.',
            'state.between'     => 'يجب اختيار حالة المادة اما مفتوحه او مغلقه.',
            'detail.required'   => 'لايوجد تفاصيل حول المادة.'
        ]);

        $course->name = Input::get("name");
        $course->level = Input::get("level");
        $course->lecturer_id = Input::get("lecturer");
        $course->state = Input::get("state");
        $course->detail = Input::get("detail");
        $success = $course->save();

        if (!$success)
            return redirect("/control-panel/courses/$course->id/edit")->with([
                "UpdateCourseMessage" => "لم يتم تعديل المادة"
            ]);

        $target = $course->id;
        $type = EventLogType::COURSE;
        $event = "تعديل المادة";
        EventLog::create($target, $type, $event);

        return redirect("/control-panel/courses")->with([
            "UpdateCourseMessage" => "تم تعديل المادة - " . $course->name
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        $course->state = CourseState::CLOSE;
        $success = $course->save();

        if (!$success)
            return redirect("/control-panel/courses")->with([
                "ArchiveCourseMessage" => "لم يتم ارشفة المادة  - " . $course->name
            ]);

        $target = $course->id;
        $type = EventLogType::COURSE;
        $event = "اغلاق المادة";
        EventLog::create($target, $type, $event);

        return redirect("/control-panel/courses")->with([
            "ArchiveCourseMessage" => "تم ارشفة المادة - " . $course->name
        ]);
    }

    /**
     * Generation exams for this course
     */
    public function generateExams()
    {
        $course = Course::findOrFail(Input::get("id"));
        $exams = Exam::where("course_id", $course->id)->get();

        if (count($exams) == 0)
        {
            DB::transaction(function (){
                $course = Course::find(Input::get("id"));
                for($i=1; $i<=4; ++$i)
                {
                    $exam = new Exam();
                    $exam->title = $course->name." - ".ExamType::getType($i);
                    $exam->course_id = $course->id;
                    $exam->type = $i;
                    $exam->state = ExamState::CLOSE;
                    $exam->mark = 0;
                    $exam->curve = 0;
                    $exam->date = null;
                    $exam->save();
                }
            });

            return redirect("/control-panel/courses")->with([
                "GenerateExamsMessage" => "تم انشاء النماذج للمادة  - " . $course->name
            ]);
        }

        return redirect("/control-panel/courses")->with([
            "GenerateExamsMessage" => "تم انشاء هذه النماذج مسبقا للمادة  - " . $course->name
        ]);
    }
}
