<?php

namespace App\Http\Controllers\ControlPanel;

use App\Models\Branch;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Auth::check();
        $currentQuestion = Question::findOrFail(Input::get("question"));
        $questions = Question::where("exam_id",$currentQuestion->exam_id)
            ->get();
        return view("ControlPanel.branch.index")->with([
            "currentQuestion" => $currentQuestion,
            "questions" => $questions
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
        $question = Question::findOrFail(Input::get("question"));
        return view("ControlPanel.branch.create")->with([
            "question" => $question
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
        $question = Question::findOrFail(Input::get("question"));
        $this->validate($request, [
            'title' => ['required'],
            'option-1' => ['required'],
            'option-2' => ['required'],
            'option-3' => ['required'],
        ], [

        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function show(Branch $branch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function edit(Branch $branch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Branch $branch)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function destroy(Branch $branch)
    {
        //
    }
}
