<?php

namespace App\Http\Controllers\ControlPanel;

use App\Enums\ExamType;
use App\Models\ExamStudent;
use App\Models\StudentDocument;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DocumentController extends Controller
{
    public function index()
    {
        Auth::check();
        return view("ControlPanel.document.index");
    }

    /**
     *  Relay students' grades to their documents
     *
     */
    public function creation()
    {
        $examsStudents = ExamStudent::all();

        foreach ($examsStudents as $examStudent)
        {
            $document = StudentDocument::where('student_id', $examStudent->student->id)
                ->where('course_id', $examStudent->exam->course->id)
                ->first();

            //Update document
            if ($document)
            {
                $document->first_month_score = ($examStudent->exam->type == ExamType::FIRST_MONTH)?$examStudent->score:$document->first_month_score;
                $document->second_month_score = ($examStudent->exam->type == ExamType::SECOND_MONTH)?$examStudent->score:$document->second_month_score;
                $document->final_first_score = ($examStudent->exam->type == ExamType::FINAL_FIRST_ROLE)?$examStudent->score:$document->final_first_score;
                $document->final_second_score = ($examStudent->exam->type == ExamType::FINAL_SECOND_ROLE)?$examStudent->score:$document->final_second_score;
            }

            //Create document
            if (!$document)
            {
                $document = new StudentDocument();
                $document->student_id = $examStudent->student->id;
                $document->course_id = $examStudent->exam->course->id;
                $document->first_month_score = ($examStudent->exam->type == ExamType::FIRST_MONTH)?$examStudent->score:0.0;
                $document->second_month_score = ($examStudent->exam->type == ExamType::SECOND_MONTH)?$examStudent->score:0.0;
                $document->final_first_score = ($examStudent->exam->type == ExamType::FINAL_FIRST_ROLE)?$examStudent->score:0;
                $document->final_second_score = ($examStudent->exam->type == ExamType::FINAL_SECOND_ROLE)?$examStudent->score:0.00;
                $document->year = date("Y");
            }

            $document->save();
        }

        dd("OK");
    }
}
