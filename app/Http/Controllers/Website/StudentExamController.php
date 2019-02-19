<?php

namespace App\Http\Controllers\Website;

use App\Models\Answer;
use App\Models\Exam;
use App\Models\ExamStudent;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;

class StudentExamController extends Controller
{

    public function exams()
    {
        try{
        $student=Student::where('remember_token',Cookie::get('remember_me'))->first();
        //return response()->json($student->notFinishedExams);
            return view("Website/exams",compact('student',$student));
    }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function exam($id)
    {
        try{
           $exam= Exam::find($id);
            return view("Website/exam",compact('exam',$exam));
    }
    catch (\Exception $e) {
        return $e->getMessage();
    }
    }

    public function store(Request $request)
    {
        try{
        $student=Student::where('remember_token',Cookie::get('remember_me'))->first();
        Answer::where('branch_id',$request->id)->delete();
        $answer=new Answer;
        $answer->student_id=$student->id;
        $answer->branch_id=$request->id;
        $answer->text=$request->val;
        $answer->save();
        return response()->json('ok');
        }
        catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function finish(Request $request)
    {
        $examStudent = ExamStudent::where('exam_id',$request->id)->first();
        $examStudent->state =2;
        $examStudent->save();
        return response()->json($examStudent);
    }

    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
