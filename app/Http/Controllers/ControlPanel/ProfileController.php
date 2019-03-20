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
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rule;
use phpDocumentor\Reflection\Types\Null_;

class ProfileController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param $account
     * @return \Illuminate\Http\Response
     */
    public function show($account)
    {
        Auth::check();
        $account = self::getAccount($account);
        return view("ControlPanel.profile.show")->with([
            "account"  => $account,
            "events" => $account->events()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $account
     * @return \Illuminate\Http\Response
     */
    public function edit($account)
    {
        Auth::check();
        $account = self::getAccount($account);
        return view("ControlPanel.profile.edit")->with([
            "account" => $account,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $account
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $account)
    {
        Auth::check();
        $account = self::getAccount($account);

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
                return redirect("/control-panel/profile/$account->id/edit/change-info")->with([
                    "UpdateProfileMessage" => "تم تحديث معلوماتك الشخصية"
                ]);
            else
                return redirect("/control-panel/profile/$account->id/edit/change-info")->with([
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
                //Update account and logout from current device
                $account->password = md5(Input::get("password"));
                //logout from all devices
                if (Input::get("logout") == true)
                    $account->remember_token = null;
                $account->save();

                //Store event log
                $target = $account->id;
                $type = EventLogType::PROFILE;
                $event = "تغيير كلمة المرور";
                EventLog::create($target, $type, $event);

                //Remove session
                session()->remove("EXAM_SYSTEM_ACCOUNT_ID");
                session()->remove("EXAM_SYSTEM_ACCOUNT_NAME");
                session()->remove("EXAM_SYSTEM_ACCOUNT_USERNAME");
                session()->remove("EXAM_SYSTEM_ACCOUNT_STATE");
                session()->remove("EXAM_SYSTEM_ACCOUNT_TOKEN");
                session()->remove("EXAM_SYSTEM_ACCOUNT_TYPE");
                session()->save();

                //Remove cookies
                Cookie::queue(cookie()->forget("EXAM_SYSTEM_ACCOUNT_TOKEN"));
                Cookie::queue(cookie()->forget("EXAM_SYSTEM_ACCOUNT_TYPE"));
            });

            if (is_null($exception))
                return redirect("/control-panel/login")->with([
                    "ChangePasswordMessage" => "تم تغيير كلمة المرور، يرجى اعادة تسجيل الدخول."
                ]);
            else
                return redirect("/control-panel/profile/$account->id/edit/change-password")->with([
                    "UpdateProfileMessage" => "لم يتم تغيير كلمة المرور"
                ]);
        }

        //Otherwise
        return abort(404);
    }

    /**
     * Get the specified resource.
     *
     * @param $id
     */
    private static function getAccount($account){
        if (session()->get("EXAM_SYSTEM_ACCOUNT_TYPE") == AccountType::MANAGER)
            return Admin::findOrFail($account);
        elseif (session()->get("EXAM_SYSTEM_ACCOUNT_TYPE") == AccountType::LECTURER)
            return Lecturer::findOrFail($account);
        else
            return abort(404);
    }
}