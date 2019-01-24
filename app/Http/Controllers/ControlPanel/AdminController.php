<?php

namespace App\Http\Controllers\ControlPanel;

use App\Enums\AccountState;
use App\Enums\AccountType;
use App\Enums\EventLogType;
use App\Models\Admin;
use App\Models\EventLog;
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
        Auth::check();
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
        return view("ControlPanel.admin.create");
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
            'name'                  => 'required',
            'username'              => 'required|unique:admin,username',
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

        $admin = new Admin();
        $admin->name = Input::get("name");
        $admin->username = Input::get("username");
        $admin->password = md5(Input::get("password"));
        $admin->state = Input::get("state");
        $admin->session = null;
        $admin->date = date("Y-m-d");
        $success = $admin->save();

        if (!$success)
            return redirect("/control-panel/admins/create")->with([
                "CreateAdminMessage" => "لم تتم عملية الاضافة بنجاح"
            ]);

        $target = $admin->id;
        $type = EventLogType::ADMIN;
        $event = "اضافة حساب جديد";
        EventLog::create($target, $type, $event);

        return redirect("/control-panel/admins/create")->with([
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
        $events = EventLog::where("account_id", $admin->id)
            ->where("account_type",AccountType::MANAGER)
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
        return view("ControlPanel.admin.edit")->with([
            "admin" => $admin
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

            $admin->password = md5(Input::get("password"));

            /**
             * Store auto login for current admin.
             * If current admin change your password from admins.
             */
            if (session()->get("EXAM_SYSTEM_ACCOUNT_ID") != $admin->id)
                $admin->session = null;

            $success = $admin->save();

            if (!$success)
                return redirect("/control-panel/admins/$admin->id/edit?type=change-password")->with([
                    "UpdateAdminMessage" => "لم يتم تغيير كلمة المرور"
                ]);

            $target = $admin->id;
            $type = EventLogType::ADMIN;
            $event = "تغيير كلمة المرور";
            EventLog::create($target, $type, $event);

            return redirect("/control-panel/admins")->with([
                "UpdateAdminMessage" => "تم تغيير كلمة المرور - " . $admin->name
            ]);
        }
        //For Change Info Account
        else {
            $this->validate($request, [
                'name'     => 'required',
                'username' => ["required", Rule::unique('admin')->ignore($admin->id)],
                'state'    => 'required|integer|between:1,2'
            ], [
                'name.required'     => 'الاسم الحقيقي فارغ.',
                'username.required' => 'اسم المستخدم فارغ.',
                'username.unique'   => 'يوجد مستخدم اخر بنفس الاسم.',
                'state.required'    => 'يجب اختيار حالة الحساب.',
                'state.integer'     => 'يجب اختيار حالة الحساب اما 1 او 2.',
                'state.between'     => 'يجب اختيار حالة الحساب اما مفتوح او مغلق.'
            ]);

            $admin->name = Input::get("name");
            $admin->username = Input::get("username");
            $admin->state = Input::get("state");
            $success = $admin->save();

            if (!$success)
                return redirect("/control-panel/admins/$admin->id/edit?type=change-info")->with([
                    "UpdateAdminMessage" => "لم يتم تحديث المعلومات"
                ]);

            /**
             * Update session for current admin.
             * If current admin change your info from admins.
             */
            if (session()->get("EXAM_SYSTEM_ACCOUNT_ID") == $admin->id)
            {
                session()->put('EXAM_SYSTEM_ACCOUNT_NAME', $admin->name);
                session()->put('EXAM_SYSTEM_ACCOUNT_USERNAME', $admin->username);
                session()->put('EXAM_SYSTEM_ACCOUNT_STATE', $admin->state);
                session()->save();
            }

            $target = $admin->id;
            $type = EventLogType::ADMIN;
            $event = "تعديل الحساب";
            EventLog::create($target, $type, $event);

            return redirect("/control-panel/admins")->with([
                "UpdateAdminMessage" => "تم تحديث المعلومات - " . $admin->name
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
        $admin->state = AccountState::CLOSE;
        $success = $admin->save();

        if (!$success)
            return redirect("/control-panel/admins")->with([
                "ArchiveAdminMessage" => "لم يتم غلق حساب - " . $admin->name
            ]);

        /**
         * Update session for current admin.
         * If current admin archive from admins.
         */
        if (session()->get("EXAM_SYSTEM_ACCOUNT_ID") == $admin->id)
        {
            session()->put('EXAM_SYSTEM_ACCOUNT_STATE', $admin->state);
            session()->save();
        }

        $target = $admin->id;
        $type = EventLogType::ADMIN;
        $event = "اغلاق الحساب";
        EventLog::create($target, $type, $event);

        return redirect("/control-panel/admins")->with([
            "ArchiveAdminMessage" => "تم غلق حساب - " . $admin->name
        ]);
    }
}