<?php

namespace App\Http\Controllers\ControlPanel;

use App\Enums\AdminType;
use App\Enums\EventLogType;
use App\Models\Admin;
use App\Models\EventLog;
use App\Models\Lecturer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admins = Admin::all();
        return view("ControlPanel.admin.index")->with([
            "admins" => $admins
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
        return view("ControlPanel.admin.create")->with([
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
        $rule = [
            'name' => 'required',
            'username' => 'required|unique:admin,username',
            'password' => 'required|min:8',
            'password_confirmation' => 'required_with:password|same:password',
            'type' => 'required',
            'lecturer_id' => 'required_if:type,2',
            'state' => 'required'
        ];

        $ruleMessages = [
            'name.required' => 'الاسم الحقيقي فارغ.',
            'username.required' => 'اسم المستخدم فارغ.',
            'username.unique' => 'يوجد مستخدم اخر بنفس الاسم.',
            'password.required' => 'كلمة المرور فارغ.',
            'password.min' => 'كلمة المرور يجب ان لاتقل عن 8 حروف.',
            'password_confirmation.required_with' => 'يرجى اعادة كتابة كلمة المرور.',
            'password_confirmation.same' => 'كلمتا المرور ليس متطابقتان.',
            'type.required' => 'يجب اختيار نوع الحساب.',
            'lecturer_id.required_if' => 'يجب اختيار الاستاذ لان نوع الحساب هو استاذ.',
            'state.required' => 'يجب اختيار حالة الحساب.'
        ];

        $this->validate($request, $rule, $ruleMessages);

        $admin = new Admin();
        $admin->name = Input::get("name");
        $admin->username = Input::get("username");
        $admin->password = md5(Input::get("password"));
        $admin->type = Input::get("type");
        $admin->lecturer_id = ($admin->type == AdminType::LECTURER) ? Input::get("lecturer_id") : null;
        $admin->state = Input::get("state");
        $admin->session = null;
        $admin->date = date("Y-m-d");
        $success = $admin->save();

        if (!$success)
            return redirect("control-panel/admins/create")->with([
                "CreateAdminMessage" => "لم تتم عملية الاضافة بنجاح"
            ]);

        $source = session()->get("EXAM_SYSTEM_ADMIN_ID");
        $destination = $admin->id;
        $type = EventLogType::ADMIN;
        $event = "اضافة حساب جديد";
        EventLog::create($source, $destination, $type, $event);

        return redirect("control-panel/admins/create")->with([
            "CreateAdminMessage" => "تمت عملية الاضافة بنجاح"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        $events = EventLog::where("source", $admin->id)
            ->orderBy("id","DESC")
            ->get();
        return view("ControlPanel.admin.show")->with([
            "admin" => $admin,
            "events" => $events
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $admin)
    {
        $lecturers = Lecturer::all();
        return view("ControlPanel.admin.edit")->with([
            "admin" => $admin,
            "lecturers" => $lecturers
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Admin $admin
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Admin $admin)
    {
        //For Change Password
        if (Input::get("type") == "change-password") {
            $rule = [
                'password' => 'required|min:8',
                'password_confirmation' => 'required_with:password|same:password'
            ];

            $ruleMessages = [
                'password.required' => 'كلمة المرور الجديدة فارغه.',
                'password.min' => 'كلمة المرور الجديدة يجب ان لاتقل عن 8 حروف.',
                'password_confirmation.required_with' => 'يرجى اعادة كتابة كلمة المرور الجديدة.',
                'password_confirmation.same' => 'كلمتا المرور ليس متطابقتان.'
            ];

            $this->validate($request, $rule, $ruleMessages);

            $admin->password = md5(Input::get("password"));
            $admin->session = null;
            $success = $admin->save();

            if (!$success)
                return redirect("control-panel/admins/$admin->id/edit?type=change-password")->with([
                    "UpdateAdminMessage" => "لم يتم تغيير كلمة المرور"
                ]);

            $source = session()->get("EXAM_SYSTEM_ADMIN_ID");
            $destination = $admin->id;
            $type = EventLogType::ADMIN;
            $event = "تغيير كلمة المرور";
            EventLog::create($source, $destination, $type, $event);

            return redirect("control-panel/admins/$admin->id/edit?type=change-password")->with([
                "UpdateAdminMessage" => "تم تغيير كلمة المرور"
            ]);
        }
        //For Change Info Account
        else {
            $rule = [
                'name' => 'required',
                'username' => ["required", Rule::unique('admin')->ignore($admin->id)],
                'type' => 'required',
                'lecturer_id' => 'required_if:type,2',
                'state' => 'required'
            ];

            $ruleMessages = [
                'name.required' => 'الاسم الحقيقي فارغ.',
                'username.required' => 'اسم المستخدم فارغ.',
                'username.unique' => 'يوجد مستخدم اخر بنفس الاسم.',
                'type.required' => 'يجب اختيار نوع الحساب.',
                'lecturer_id.required_if' => 'يجب اختيار الاستاذ لان نوع الحساب هو استاذ.',
                'state.required' => 'يجب اختيار حالة الحساب.'
            ];

            $this->validate($request, $rule, $ruleMessages);

            $admin->name = Input::get("name");
            $admin->username = Input::get("username");
            $admin->type = Input::get("type");
            $admin->lecturer_id = ($admin->type == AdminType::LECTURER) ? Input::get("lecturer_id") : null;
            $admin->state = Input::get("state");
            $success = $admin->save();

            if (!$success)
                return redirect("control-panel/admins/$admin->id/edit?type=change-info")->with([
                    "UpdateAdminMessage" => "لم يتم تحديث المعلومات"
                ]);

            $source = session()->get("EXAM_SYSTEM_ADMIN_ID");
            $destination = $admin->id;
            $type = EventLogType::ADMIN;
            $event = "تعديل الحساب";
            EventLog::create($source, $destination, $type, $event);

            return redirect("control-panel/admins/$admin->id/edit?type=change-info")->with([
                "UpdateAdminMessage" => "تم تحديث المعلومات"
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        //
    }
}