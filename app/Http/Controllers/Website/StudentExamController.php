<?php

namespace App\Http\Controllers\Website;

use App\Models\Exam;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;

class StudentExamController extends Controller
{

    public function exams()
    {
        $studentExams=Student::where('remember_token',Cookie::get('remember_me'))->with('exams')->has('exams')->first();
        return response()
            ->json($studentExams->exams);
    }

    public function exam(Request $request)
    {
        $exam=Exam::find($request->id);
        return response()
            ->json($exam);
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
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
