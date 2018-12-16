<?php

namespace App\Http\Controllers\ControlPanel;

use App\Models\Admin;
use App\Models\Lecturer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

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
            'state' => 'required',
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
            'state.required' => 'يجب اختيار حالة الحساب.',
        ];

        $this->validate($request, $rule, $ruleMessages);

        $admin = new Admin();
        $admin->name = Input::get("name");
        $admin->username = Input::get("username");
        $admin->password = md5(Input::get("password"));
        $admin->type = Input::get("type");
        $admin->lecturer_id = Input::get("lecturer_id",null);
        $admin->state = Input::get("state");
        $admin->session = Input::get("session");
        $admin->date = date("Y-m-d");
        $success = $admin->save();

        if(!$success)
            return redirect("control-panel/admins/create")->with([
                "CreateAdminMessage" => "لم تتم عملية الاضافة بنجاح."
            ]);

        return redirect("control-panel/admins/create")->with([
            "CreateAdminMessage" => "تمت عملية الاضافة بنجاح."
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $admin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Admin $admin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        //
    }
}
