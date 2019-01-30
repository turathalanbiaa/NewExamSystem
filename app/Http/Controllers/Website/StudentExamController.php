<?php

namespace App\Http\Controllers\Website;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;

class StudentExamController extends Controller
{

    public function index()
    {
        $studentExams=Student::where('remember_token',Cookie::get('remember_me'))->with('exams')->has('exams')->first();
        return response()
            ->json($studentExams->exams);
    }

    public function create()
    {
        //
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
