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
        Auth::check();
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
        Auth::check();
        return view("ControlPanel.course.create")->with([
            "lecturers" => self::getLecturers()
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
        $this->validate($request, [
            'name'     => 'required',
            'level'    => 'required|integer|between:1,7',
            'lecturer' => ['required', Rule::in(self::getLecturers()->pluck("id")->toArray())],
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
                "CreateCourseMessage" => "لم تتم عملية اضافة المادة بنجاح",
                "TypeMessage" => "Error"
            ]);

        /**
         * Keep event log
         */
        $target = $course->id;
        $type = EventLogType::COURSE;
        $event = "اضافة مادة - " . $course->name;
        EventLog::create($target, $type, $event);

        return redirect("/control-panel/courses/create")->with([
            "CreateCourseMessage" => "تمت عملية اضافة المادة بنجاح - " . $course->name
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
        Auth::check();
        return "Please, Don't make this again";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        Auth::check();
        return view("ControlPanel.course.edit")->with([
            "course"    => $course,
            "lecturers" => self::getLecturers()
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
        Auth::check();
        $this->validate($request, [
            'name'     => 'required',
            'level'    => 'required|integer|between:1,7',
            'lecturer' => ['required', Rule::in(self::getLecturers()->pluck("id")->toArray())],
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
                "UpdateCourseMessage" => "لم يتم تعديل المادة - " . $course->name
            ]);

        /**
         * Keep event log
         */
        $target = $course->id;
        $type = EventLogType::COURSE;
        $event = "تعديل المادة - " . $course->name;
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
        Auth::check();
        $course->state = CourseState::CLOSE;
        $success = $course->save();

        if (!$success)
            return redirect("/control-panel/courses")->with([
                "ArchiveCourseMessage" => "لم يتم غلق المادة  - " . $course->name,
                "TypeMessage" => "Error"
            ]);

        /**
         * Keep event log
         */
        $target = $course->id;
        $type = EventLogType::COURSE;
        $event = "اغلاق المادة - " . $course->name;
        EventLog::create($target, $type, $event);

        return redirect("/control-panel/courses")->with([
            "ArchiveCourseMessage" => "تم غلق المادة - " . $course->name
        ]);
    }

    /**
     * Get the specified lecturers from storage
     *
     * @return mixed
     */
    private static function getLecturers()
    {
        return Lecturer::all();
    }
}
