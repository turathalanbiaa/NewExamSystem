<?php

namespace App\Http\Controllers\ControlPanel;

use App\Enums\AccountType;
use App\Enums\EventLogType;
use App\Models\Admin;
use App\Models\EventLog;
use App\Models\Lecturer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        dd(getenv("USERNAME"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Auth::check();
        $account = self::getAccount($id);
        return view("ControlPanel.profile.show")->with([
            "account"  => $account,
            "events" => $account->events()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Auth::check();
        $account = self::getAccount($id);
        return view("ControlPanel.profile.edit")->with([
            "account" => $account,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        Auth::check();
        $account = self::getAccount($id);

        //For change info
        if (Input::get("type") == "change-info")
        {
            $table = (session()->get("EXAM_SYSTEM_ACCOUNT_TYPE")==AccountType::MANAGER)?"admin":"lecturer";
            //Validation
            $this->validate($request, [
                'name'     => 'required',
                'username' => ["required", Rule::unique($table)->ignore($account->id)]
            ], [
                'name.required'     => 'الاسم الحقيقي فارغ.',
                'username.required' => 'اسم المستخدم فارغ.',
                'username.unique'   => 'اسم المستخدم هذا مستخدم بالفعل، يرجى استخدام اسم آخر.',
                'state.required'    => 'يجب اختيار حالة الحساب.',
                'state.integer'     => 'يجب اختيار حالة الحساب اما 1 او 2.',
                'state.between'     => 'يجب اختيار حالة الحساب اما مفتوح او مغلق.'
            ]);

            //Transaction
            $exception = DB::transaction(function () use ($account){
                //Update account
                $account->name = Input::get("name");
                $account->username = Input::get("username");
                $account->save();

                //Store event log
                $target = $account->id;
                $type = EventLogType::PROFILE;
                $event = "تحديث معلوماتي الشخصية";
                EventLog::create($target, $type, $event);

                //Update session
                session()->put("EXAM_SYSTEM_ACCOUNT_NAME",$account->name);
                session()->put("EXAM_SYSTEM_ACCOUNT_USERNAME",$account->username);
                session()->save();
            });

            if (is_null($exception))
                return redirect("/control-panel/profile/$account->id/edit?type=change-info")->with([
                    "UpdateProfileMessage" => "تم تحديث معلوماتك الشخصية"
                ]);
            else
                return redirect("/control-panel/lecturers/$account->id/edit?type=change-info")->with([
                    "UpdateProfileMessage" => "لم يتم تحديث معلوماتك الشخصية"
                ]);
        }

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
            $exception = DB::transaction(function () use ($account){
                //Update account
                $account->password = md5(Input::get("password"));
                $account->save();

                //Store event log
                $target = $account->id;
                $type = EventLogType::PROFILE;
                $event = "تغيير كلمة المرور";
                EventLog::create($target, $type, $event);
            });

            if (is_null($exception))
                return redirect("/control-panel/profile/$account->id/edit?type=change-password")->with([
                    "UpdateAccountMessage" => "تم تغيير كلمة المرور"
                ]);
            else
                return redirect("/control-panel/profile/$account->id/edit?type=change-password")->with([
                    "UpdateAccountMessage" => "لم يتم تغيير كلمة المرور"
                ]);
        }

        //Otherwise
        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Get the specified resource.
     *
     * @param $id
     */
    private static function getAccount($id){
        if (session()->get("EXAM_SYSTEM_ACCOUNT_TYPE") == AccountType::MANAGER)
            return Admin::findOrFail($id);
        elseif (session()->get("EXAM_SYSTEM_ACCOUNT_TYPE") == AccountType::LECTURER)
            return Lecturer::findOrFail($id);
        else
            return abort(404);
    }
}
