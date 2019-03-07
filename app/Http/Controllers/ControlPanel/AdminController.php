<?php

namespace App\Http\Controllers\ControlPanel;

use App\Enums\AccountState;
use App\Enums\AccountType;
use App\Enums\EventLogType;
use App\Models\Admin;
use App\Models\EventLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
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
        Auth::check();
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
        Auth::check();
        //Validation
        $this->validate($request, [
            'name'                  => 'required',
            'username'              => 'required|unique:admin,username',
            'password'              => 'required|min:8',
            'password_confirmation' => 'required_with:password|same:password',
            'state'                 => 'required|integer|between:1,2'
        ], [
            'name.required'                       => 'الاسم الحقيقي فارغ.',
            'username.required'                   => 'اسم المستخدم فارغ.',
            'username.unique'                     => 'اسم المستخدم هذا مستخدم بالفعل، يرجى استخدام اسم آخر.',
            'password.required'                   => 'كلمة المرور فارغ.',
            'password.min'                        => 'كلمة المرور يجب ان لاتقل عن 8 حروف.',
            'password_confirmation.required_with' => 'يرجى اعادة كتابة كلمة المرور.',
            'password_confirmation.same'          => 'كلمتا المرور ليس متطابقتان.',
            'state.required'                      => 'يجب اختيار حالة الحساب.',
            'state.integer'                       => 'يجب اختيار حالة الحساب اما 1 او 2.',
            'state.between'                       => 'يجب اختيار حالة الحساب اما مفتوح او مغلق.'
        ]);

        //Transaction
        $exception = DB::transaction(function () use (&$admin){
            $admin = new Admin();
            $admin->name = Input::get("name");
            $admin->username = Input::get("username");
            $admin->password = md5(Input::get("password"));
            $admin->state = Input::get("state");
            $admin->remember_token = null;
            $admin->date = date("Y-m-d");
            $admin->save();

            //Store event log
            $target = $admin->id;
            $type = EventLogType::ADMIN;
            $event = "اضافة مدير " . $admin->name;
            EventLog::create($target, $type, $event);
        });

        if (is_null($exception))
            return redirect("/control-panel/admins/create")->with([
                "CreateAdminMessage" => "تمت عملية اضافة المدير " . $admin->name . " بنجاح"
            ]);
        else
            return redirect("/control-panel/admins/create")->with([
                "CreateAdminMessage" => "لم تتم عملية اضافة المدير بنجاح",
                "TypeMessage" => "Error"
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
        Auth::check();
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
        Auth::check();
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
        Auth::check();
        //For change password
        if (Input::get("type") == "change-password")
        {
            //Validation
            $this->validate($request, [
                'password'              => 'required|min:8',
                'password_confirmation' => 'required_with:password|same:password'
            ], [
                'password.required'                   => 'كلمة المرور الجديدة فارغه.',
                'password.min'                        => 'كلمة المرور الجديدة يجب ان لاتقل عن 8 حروف.',
                'password_confirmation.required_with' => 'يرجى اعادة كتابة كلمة المرور الجديدة.',
                'password_confirmation.same'          => 'كلمتا المرور ليس متطابقتان.'
            ]);

            //Transaction
            $exception = DB::transaction(function () use ($admin){
                //Update admin and remove session for all managers except super admin
                $admin->password = md5(Input::get("password"));
                $admin->remember_token = (session()->get("EXAM_SYSTEM_ACCOUNT_ID") != 1)?null:$admin->remember_token;
                $admin->save();

                //Store event log
                $target = $admin->id;
                $type = EventLogType::ADMIN;
                $event = "تغيير كلمة المرور المدير " . $admin->name;
                EventLog::create($target, $type, $event);
            });

            if (is_null($exception))
                return redirect("/control-panel/admins")->with([
                    "UpdateAdminMessage" => "تم تغيير كلمة المرور المدير " . $admin->name
                ]);
            else
                return redirect("/control-panel/admins/$admin->id/edit?type=change-password")->with([
                    "UpdateAdminMessage" => "لم يتم تغيير كلمة المرور المدير"
                ]);
        }

        //For change info account
        if (Input::get("type") == "change-info")
        {
            //Validation
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

            //Transaction
            $exception = DB::transaction(function () use ($admin){
                //Update Admin
                $admin->name = Input::get("name");
                $admin->username = Input::get("username");
                $admin->state = Input::get("state");
                $admin->save();

                //Store event log
                $target = $admin->id;
                $type = EventLogType::ADMIN;
                $event = "تعديل الحساب المدير " . $admin->name;
                EventLog::create($target, $type, $event);

                //Update session for super admin
                if (session()->get("EXAM_SYSTEM_ACCOUNT_ID") == 1)
                {
                    session()->put('EXAM_SYSTEM_ACCOUNT_NAME', $admin->name);
                    session()->put('EXAM_SYSTEM_ACCOUNT_USERNAME', $admin->username);
                    session()->put('EXAM_SYSTEM_ACCOUNT_STATE', $admin->state);
                    session()->save();
                }
            });

            if (is_null($exception))
                return redirect("/control-panel/admins")->with([
                    "UpdateAdminMessage" => "تم تحديث المعلومات المدير " . $admin->name
                ]);
            else
                return redirect("/control-panel/admins/$admin->id/edit?type=change-info")->with([
                    "UpdateAdminMessage" => "لم يتم تحديث المعلومات المدير"
                ]);
        }

        //Otherwise
        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        Auth::check();

        //Transaction
        $exception = DB::transaction(function () use ($admin){
            //Update Admin
            $admin->state = AccountState::CLOSE;
            $admin->session = null;
            $admin->save();

            //Store event log
            $target = $admin->id;
            $type = EventLogType::ADMIN;
            $event = "اغلاق الحساب المدير " . $admin->name;
            EventLog::create($target, $type, $event);

            //Update session for super admin
            if ($admin->id == 1)
            {
                session()->put('EXAM_SYSTEM_ACCOUNT_STATE', $admin->state);
                session()->save();
            }
        });

        if (is_null($exception))
            return redirect("/control-panel/admins")->with([
                "ArchiveAdminMessage" => "تم غلق حساب المدير " . $admin->name
            ]);
        else
            return redirect("/control-panel/admins")->with([
                "ArchiveAdminMessage" => "لم يتم غلق حساب المدير " . $admin->name,
                "TypeMessage" => "Error"
            ]);
    }
}