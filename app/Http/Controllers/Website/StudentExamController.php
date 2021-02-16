<?php

namespace App\Http\Controllers\Website;

use App\Enums\AnswerCorrectionState;
use App\Enums\ExamState;
use App\Enums\ExamStudentState;
use App\Models\Answer;
use App\Models\Branch;
use App\Models\Exam;
use App\Models\ExamStudent;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
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


    public function store(Request $request): JsonResponse {
        $student = Student::where('remember_token', Cookie::get('remember_me'))
            ->first();

        $exam = Exam::where([
            "id" => $request->input("exam"),
            array("state", "!=", ExamState::CLOSE)
        ])
            ->first();

        $examStudent = ExamStudent::where([
            "student_id" => $student->id,
            "exam_id"    => $exam->id
        ])
            ->first();

        if (!$examStudent)
            return response()->json([
                "background" => "bg-danger",
                "icon"       => "fa-bomb",
                "message"    => "دخول غير مصرح به، يرجى اعادة تحميل الصفحة"
            ]);

        if ($exam->state == ExamState::END || $examStudent->state == ExamStudentState::FINISHED)
            return response()->json([
                "background" => "bg-info",
                "icon"       => "fa-exclamation",
                "message"    => "تم انهاء المتحان، يرجى الذهاب الى الامتحانات المنتهية"
            ]);

        $branch = Branch::where("id", $request->input("branch"))
            ->whereIn("question_id", $exam->questions->pluck("id")->toArray())
            ->first();

        if (!$branch)
            return response()->json([
                "background" => "bg-warning",
                "icon"       => "fa-skull-crossbones",
                "message"    => "تحذير !!!، يرجى اعادة تحميل الصفحة"
            ]);

        Answer::updateOrCreate([
            "student_id" => $student->id,
            "branch_id"  => $branch->id
        ], [
            "text"       => $request->input("answer"),
            "time"       => Carbon::now(),
            "score"      => 0.0,
            "correction" => AnswerCorrectionState::UNCORRECTED
        ]);

        return response()->json([
            "background" => "bg-success",
            "icon"       => "fa-check",
            "message"    => "تم حفظ الاجابة بنجاح"
        ]);
    }

    public function delete(Request $request): JsonResponse {
        $student = Student::where('remember_token', Cookie::get('remember_me'))
            ->first();

        $exam = Exam::where([
            "id" => $request->input("exam"),
            array("state", "!=", ExamState::CLOSE)
        ])
            ->first();

        $examStudent = ExamStudent::where([
            "student_id" => $student->id,
            "exam_id"    => $exam->id
        ])
            ->first();

        if (!$examStudent)
            return response()->json([
                "background" => "bg-danger",
                "icon"       => "fa-bomb",
                "message"    => "دخول غير مصرح به، يرجى اعادة تحميل الصفحة"
            ]);

        if ($exam->state == ExamState::END || $examStudent->state == ExamStudentState::FINISHED)
            return response()->json([
                "background" => "bg-info",
                "icon"       => "fa-exclamation",
                "message"    => "تم انهاء المتحان، يرجى الذهاب الى الامتحانات المنتهية"
            ]);

        $branch = Branch::where("id", $request->input("branch"))
            ->whereIn("question_id", $exam->questions->pluck("id")->toArray())
            ->first();

        if (!$branch)
            return response()->json([
                "background" => "bg-warning",
                "icon"       => "fa-skull-crossbones",
                "message"    => "تحذير !!!، يرجى اعادة تحميل الصفحة"
            ]);

        Answer::where([
            "student_id" => $student->id,
            "branch_id"  => $branch->id
        ])->delete();

        return response()->json([
            "background" => "bg-danger",
            "icon"       => "fa-check",
            "message"    => "تم حذف الاجابة بنجاح"
        ]);
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
