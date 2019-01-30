<?php

namespace App\Http\Controllers\ControlPanel;

use App\Enums\AccountState;
use App\Enums\AccountType;
use App\Enums\EventLogType;
use App\Models\EventLog;
use App\Models\Lecturer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rule;

class LecturerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Auth::check();
        $lecturers = Lecturer::all();
        return view("ControlPanel.lecturer.index")->with([
            "lecturers" => $lecturers
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
        return view("ControlPanel.lecturer.create");
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
            'name'                  => 'required',
            'username'              => 'required|unique:lecturer,username',
            'password'              => 'required|min:8',
            'password_confirmation' => 'required_with:password|same:password',
            'state'                 => 'required|integer|between:1,2'
        ], [
            'name.required'                       => 'الاسم الحقيقي فارغ.',
            'username.required'                   => 'اسم المستخدم فارغ.',
            'username.unique'                     => 'يوجد مستخدم اخر بنفس الاسم.',
            'password.required'                   => 'كلمة المرور فارغ.',
            'password.min'                        => 'كلمة المرور يجب ان لاتقل عن 8 حروف.',
            'password_confirmation.required_with' => 'يرجى اعادة كتابة كلمة المرور.',
            'password_confirmation.same'          => 'كلمتا المرور ليس متطابقتان.',
            'state.required'                      => 'يجب اختيار حالة الحساب.',
            'state.integer'                       => 'يجب اختيار حالة الحساب اما 1 او 2.',
            'state.between'                       => 'يجب اختيار حالة الحساب اما مفتوح او مغلق.'
        ]);

        $lecturer = new Lecturer();
        $lecturer->name = Input::get("name");
        $lecturer->username = Input::get("username");
        $lecturer->password = md5(Input::get("password"));
        $lecturer->state = Input::get("state");
        $lecturer->session = null;
        $lecturer->date = date("Y-m-d");
        $success = $lecturer->save();

        if (!$success)
            return redirect("/control-panel/lecturers/create")->with([
                "CreateLecturerMessage" => "لم تتم عملية الاضافة الاستاذ بنجاح",
                "TypeMessage" => "Error"
            ]);

        /**
         * Keep event log
         */
        $target = $lecturer->id;
        $type = EventLogType::LECTURER;
        $event = "اضافة حساب استاذ - " . $lecturer->name;
        EventLog::create($target, $type, $event);

        return redirect("/control-panel/lecturers/create")->with([
            "CreateLecturerMessage" => "تمت عملية الاضافة الاستاذ بنجاح - " . $lecturer->name
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lecturer  $lecturer
     * @return \Illuminate\Http\Response
     */
    public function show(Lecturer $lecturer)
    {
        Auth::check();
        $events = EventLog::where("account_id", $lecturer->id)
            ->where("account_type",AccountType::LECTURER)
            ->orderBy("id","DESC")
            ->get();
        return view("ControlPanel.lecturer.show")->with([
            "lecturer" => $lecturer,
            "events" => $events
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lecturer  $lecturer
     * @return \Illuminate\Http\Response
     */
    public function edit(Lecturer $lecturer)
    {
        Auth::check();
        return view("ControlPanel.lecturer.edit")->with([
            "lecturer" => $lecturer
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Lecturer $lecturer
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Lecturer $lecturer)
    {
        Auth::check();
        /**
         * For change password
         */
        if (Input::get("type") == "change-password")
        {
            $this->validate($request, [
                'password'              => 'required|min:8',
                'password_confirmation' => 'required_with:password|same:password'
            ], [
                'password.required'                   => 'كلمة المرور الجديدة فارغه.',
                'password.min'                        => 'كلمة المرور الجديدة يجب ان لاتقل عن 8 حروف.',
                'password_confirmation.required_with' => 'يرجى اعادة كتابة كلمة المرور الجديدة.',
                'password_confirmation.same'          => 'كلمتا المرور ليس متطابقتان.'
            ]);

            $lecturer->password = md5(Input::get("password"));
            $lecturer->session = null;
            $success = $lecturer->save();

            if (!$success)
                return redirect("/control-panel/lecturers/$lecturer->id/edit?type=change-password")->with([
                    "UpdateLecturerMessage" => "لم يتم تغيير كلمة المرور الاستاذ"
                ]);

            /**
             * Keep event log
             */
            $target = $lecturer->id;
            $type = EventLogType::LECTURER;
            $event = "تغيير كلمة المرور الاستاذ - " . $lecturer->name;
            EventLog::create($target, $type, $event);

            return redirect("/control-panel/lecturers")->with([
                "UpdateLecturerMessage" => "تم تغيير كلمة المرور الاستاذ - " . $lecturer->name
            ]);
        }
        /**
         * For change info account
         */
        else {
            $this->validate($request, [
                'name'     => 'required',
                'username' => ["required", Rule::unique('lecturer')->ignore($lecturer->id)],
                'state'    => 'required|integer|between:1,2'
            ], [
                'name.required'     => 'الاسم الحقيقي فارغ.',
                'username.required' => 'اسم المستخدم فارغ.',
                'username.unique'   => 'يوجد مستخدم اخر بنفس الاسم.',
                'state.required'    => 'يجب اختيار حالة الحساب.',
                'state.integer'     => 'يجب اختيار حالة الحساب اما 1 او 2.',
                'state.between'     => 'يجب اختيار حالة الحساب اما مفتوح او مغلق.'
            ]);

            $lecturer->name = Input::get("name");
            $lecturer->username = Input::get("username");
            $lecturer->state = Input::get("state");
            $success = $lecturer->save();

            if (!$success)
                return redirect("/control-panel/lecturers/$lecturer->id/edit?type=change-info")->with([
                    "UpdateLecturerMessage" => "لم يتم تحديث المعلومات الاستاذ"
                ]);

            /**
             * Keep event log
             */
            $target = $lecturer->id;
            $type = EventLogType::LECTURER;
            $event = "تعديل الحساب الاستاذ - " . $lecturer->name;
            EventLog::create($target, $type, $event);

            return redirect("/control-panel/lecturers")->with([
                "UpdateLecturerMessage" => "تم تحديث المعلومات الاستاذ - " . $lecturer->name
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lecturer  $lecturer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lecturer $lecturer)
    {
        Auth::check();
        $lecturer->state = AccountState::CLOSE;
        $success = $lecturer->save();

        if (!$success)
            return redirect("/control-panel/lecturers")->with([
                "ArchiveLecturerMessage" => "لم يتم غلق حساب الاستاذ - " . $lecturer->name,
                "TypeMessage" => "Error"
            ]);

        /**
         * Keep event log
         */
        $target = $lecturer->id;
        $type = EventLogType::LECTURER;
        $event = "اغلاق الحساب الاستاذ - " . $lecturer->name;
        EventLog::create($target, $type, $event);

        return redirect("/control-panel/lecturers")->with([
            "ArchiveLecturerMessage" => "تم غلق حساب الاستاذ - " . $lecturer->name
        ]);
    }
}
