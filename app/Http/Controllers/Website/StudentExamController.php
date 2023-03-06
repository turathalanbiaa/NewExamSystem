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
//        try {
            $studentNotFinishedExams = Student::where('remember_token', Cookie::get('remember_me'))->first();
            return view("Website/exams")
                ->with([
                    "studentNotFinishedExams" => $studentNotFinishedExams->notFinishedExams
                ]);
//        } catch (\Exception $e) {
//            return $e->getMessage();
//        }
    }

    public function finishedExams() {
        try {
            $studentFinishedExams = Student::where('remember_token', Cookie::get('remember_me'))->first();
            return view("Website/finishedExams", compact('studentFinishedExams', $studentFinishedExams->finishedExams ?? ""));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function nextExams() {
        try {
            $studentNotFinishedExams = Student::where('remember_token', Cookie::get('remember_me'))->first();
            return view("Website/nextExams", compact('studentNotFinishedExams', $studentNotFinishedExams->notFinishedExams ?? ""));
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

        $questions = collect();
        foreach ($exam->questions as $question) {
            $questions->push([
                "id"    => $question->id,
                "title" => implode(' ', array_slice(explode(' ', $question->title), 0, 2)),
                "count" => Answer::where("student_id", $student->id)
                    ->whereIn("branch_id", $question->branches->pluck("id")->toArray())
                    ->count()
            ]);
        }

        return view("Website/exam")->with([
            "exam"     => $exam,
            "questions" => $questions
        ]);
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
                "status"     => false,
                "background" => "bg-danger",
                "icon"       => "fa-bomb",
                "message"    => "دخول غير مصرح به، يرجى اعادة تحميل الصفحة"
            ]);

        if ($exam->state == ExamState::END || $examStudent->state == ExamStudentState::FINISHED)
            return response()->json([
                "status"     => false,
                "background" => "bg-info",
                "icon"       => "fa-exclamation",
                "message"    => "تم انهاء المتحان، يرجى الذهاب الى الامتحانات المنتهية"
            ]);

        $branch = Branch::where("id", $request->input("branch"))
            ->whereIn("question_id", $exam->questions->pluck("id")->toArray())
            ->first();

        if (!$branch)
            return response()->json([
                "status"     => false,
                "background" => "bg-warning",
                "icon"       => "fa-skull-crossbones",
                "message"    => "تحذير !!!، يرجى اعادة تحميل الصفحة"
            ]);

        $success = Answer::updateOrCreate([
            "student_id" => $student->id,
            "branch_id"  => $branch->id
        ], [
            "text"       => $request->input("answer"),
            "time"       => Carbon::now(),
            "score"      => 0.0,
            "correction" => AnswerCorrectionState::UNCORRECTED
        ]);

        if (!$success)
            return response()->json([
                "status"     => false,
                "background" => "bg-danger",
                "icon"       => "fa-frown",
                "message"    => "لم يتم حفظ الاجابة يرجى اعادة المحاولة"
            ]);

        return response()->json([
            "status"     => true,
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
                "status"     => false,
                "background" => "bg-danger",
                "icon"       => "fa-bomb",
                "message"    => "دخول غير مصرح به، يرجى اعادة تحميل الصفحة"
            ]);

        if ($exam->state == ExamState::END || $examStudent->state == ExamStudentState::FINISHED)
            return response()->json([
                "status"     => false,
                "background" => "bg-info",
                "icon"       => "fa-exclamation",
                "message"    => "تم انهاء المتحان، يرجى الذهاب الى الامتحانات المنتهية"
            ]);

        $branch = Branch::where("id", $request->input("branch"))
            ->whereIn("question_id", $exam->questions->pluck("id")->toArray())
            ->first();

        if (!$branch)
            return response()->json([
                "status"     => false,
                "background" => "bg-warning",
                "icon"       => "fa-skull-crossbones",
                "message"    => "تحذير !!!، يرجى اعادة تحميل الصفحة"
            ]);

        $success = Answer::where([
            "student_id" => $student->id,
            "branch_id"  => $branch->id
        ])->delete();

        if (!$success)
            return response()->json([
                "status"     => false,
                "background" => "bg-danger",
                "icon"       => "fa-frown",
                "message"    => "لم يتم حذف الاجابة يرجى اعادة المحاولة"
            ]);

        return response()->json([
            "status"     => true,
            "background" => "bg-success",
            "icon"       => "fa-check",
            "message"    => "تم حذف الاجابة بنجاح"
        ]);
    }

    public function finish(Request $request): JsonResponse {
        $student = Student::where('remember_token', Cookie::get('remember_me'))->first();
        $examStudent = ExamStudent::where('exam_id', $request->input("exam"))
            ->where('student_id', $student->id)
            ->first();

        if ($examStudent->state == ExamStudentState::FINISHED)
            return response()->json([
                "status"     => false,
                "background" => "bg-info",
                "icon"       => "fa-exclamation",
                "message"    => "تم انهاء الامتحان مسبقااو تم غلق الامتحان"
            ]);

        $success = $examStudent->update([
            "state" => ExamStudentState::FINISHED
        ]);

        if (!$success)
            return response()->json([
                "status"     => false,
                "background" => "bg-danger",
                "icon"       => "fa-frown",
                "message"    => "لم يتم انهاء الامتحان ، حاول مره اخرى"
            ]);

        return response()->json([
            "status"     => true,
            "background" => "bg-success",
            "icon"       => "fa-check",
            "message"    => "تم انهاء الامتحان بنجاح"
        ]);
    }
}
