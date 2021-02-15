<?php

namespace App\Http\Controllers\Website;

use App\Enums\AnswerCorrectionState;
use App\Enums\ExamState;
use App\Enums\ExamStudentState;
use App\Models\Answer;
use App\Models\Exam;
use App\Models\ExamStudent;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cookie;

class StudentExamController extends Controller
{
    public function exams() {
        try {
            $studentNotFinishedExams = Student::where('remember_token', Cookie::get('remember_me'))->first();
            return view("Website/exams", compact('studentNotFinishedExams', $studentNotFinishedExams->notFinishedExams));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function finishedExams() {
        try {
            $studentFinishedExams = Student::where('remember_token', Cookie::get('remember_me'))->first();
            return view("Website/finishedExams", compact('studentFinishedExams', $studentFinishedExams->finishedExams));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function nextExams() {
        try {
            $studentNotFinishedExams = Student::where('remember_token', Cookie::get('remember_me'))->first();
            return view("Website/nextExams", compact('studentNotFinishedExams', $studentNotFinishedExams->notFinishedExams));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function exam($id) {
        $exam = Exam::where([
            "id" => $id,
            "state" => ExamState::OPEN
        ])->first();

        if (!$exam)
            abort(404);

        $student = Student::where('remember_token', Cookie::get('remember_me'))->first();

        $examStudent = ExamStudent::where([
            "student_id" => $student->id,
            "exam_id"    => $exam->id
        ])->first();

        if (!$examStudent || $examStudent->state == ExamStudentState::FINISHED)
            abort(403, "دخول غير مصرح به");

        return view("Website/exam", compact('exam', $exam));
    }

    public function finishedExam($id) {
        $exam = Exam::where([
            "id" => $id,
            "state" => ExamState::END
        ])->first();

        if (!$exam)
            abort(404);

        $student = Student::where('remember_token', Cookie::get('remember_me'))->first();

        $examStudent = ExamStudent::where([
            "student_id" => $student->id,
            "exam_id"    => $exam->id
        ])->first();

        if (!$examStudent)
            abort(403, "دخول غير مصرح به");

        return view("Website/finishedExam", compact('exam', $exam));
    }


    public function store(Request $request)
    {
        try {
            $student = Student::where('remember_token', Cookie::get('remember_me'))->first();
            if (Answer::where(['student_id' => $student->id, 'branch_id' => $request->id])->first()) {
                Answer::where(['student_id' => $student->id, 'branch_id' => $request->id])->update(
                    ['text' => $request->val]
                );
            } else {
                $answer = new Answer;
                $answer->student_id = $student->id;
                $answer->branch_id = $request->id;
                $answer->text = $request->val;
                $answer->time = Carbon::now();
                $answer->score = 0.00000; //Default 0.00000
                $answer->correction = AnswerCorrectionState::UNCORRECTED; //Default 1
                $answer->save();
            }
            return response()->json('ok');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function delete(Request $request)
    {
        try {
            $student = Student::where('remember_token', Cookie::get('remember_me'))->first();
            if (Answer::where(['student_id' => $student->id, 'branch_id' => $request->id])->first()) {
                Answer::where(['student_id' => $student->id, 'branch_id' => $request->id])->delete();
            }
            return response()->json('ok');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function finish(Request $request)
    {
        $student = Student::where('remember_token', Cookie::get('remember_me'))->first();
        $examStudent = ExamStudent::where('exam_id', $request->id)
            ->where('student_id', $student->id)
            ->first();
        $examStudent->state = 2;
        $examStudent->save();
        return response()->json($examStudent);
    }
}
