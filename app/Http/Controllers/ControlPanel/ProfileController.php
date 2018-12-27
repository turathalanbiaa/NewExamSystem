<?php

namespace App\Http\Controllers\ControlPanel;

use App\Enums\AccountType;
use App\Models\Admin;
use App\Models\EventLog;
use App\Models\Lecturer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("ControlPanel.profile.index");
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
        $events = EventLog::where("account_id", $id)
            ->where("account_type", session()->get("EXAM_SYSTEM_ACCOUNT_TYPE"))
            ->orderBy("id","DESC")
            ->get();

        return view("ControlPanel.profile.show")->with([
            "events"  => $events
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
        switch (session()->get("EXAM_SYSTEM_ACCOUNT_TYPE"))
        {
            case (AccountType::MANAGER):
                $account = Admin::where("session", session()->get("EXAM_SYSTEM_ACCOUNT_SESSION"))
                    ->first();
                break;

            case (AccountType::LECTURER):
                $account = Admin::where("session", session()->get("EXAM_SYSTEM_ACCOUNT_SESSION"))
                    ->first();
                break;

            default: $account = false;
        }

        return view("ControlPanel.profile.edit")->with([
            "account" => $account
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
}
