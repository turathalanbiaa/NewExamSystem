<?php

namespace App\Http\Controllers\ControlPanel;

use App\Enums\Level;
use App\Models\Student;
use App\Models\StudentDocument;
use App\Http\Controllers\Controller;
use niklasravnsborg\LaravelPdf\Facades\Pdf;

class DynamicPDFController extends Controller
{
    public function studentDocument($student, $year, $season, $type)
    {
        Auth::check();
        $student = Student::findOrFail($student);
        $documents = StudentDocument::where("student_id", $student->id)
            ->where("year", $year)
            ->where("season", $season)
            ->get();

        $numberOfFailedCourses = $documents->filter(function ($document) {
            return $document->final_score < 50;
        })->count();

        //Custom config
        $config = [
            'format'      => 'A4',
            'orientation' => 'P',
            'instanceConfigurator' => function($mpdf) use ($student) {
                $mpdf->SetTitle($student->originalStudent->Name);
                $mpdf->SetAuthor("Turath Al-Alanbiaa");
                $mpdf->SetSubject("Certificate");
                $mpdf->SetCreator("Emad Al-Kabi");
            }];

        if ($type == "final-scores")
            $pdf = PDF::loadView('ControlPanel.pdf.student.final_scores',
                array(
                    "documents" => $documents,
                    "studentName" => $student->OriginalStudent->Name,
                    "level" => $documents[0]->course->level,
                    "year" => $year,
                    "role" => (($documents->filter(function ($document) {
                            return !is_null($document->final_second_score);
                        })->count() == 0)?"الاول":"الثاني"),
                    "result" => (($numberOfFailedCourses == 0)?"ناجح":($numberOfFailedCourses <= 2)?"مكمل":"راسب")
                ),
                [],
                $config
            );
        else
            $pdf = PDF::loadView('ControlPanel.pdf.student.all_scores',
                array(
                    "documents" => $documents,
                    "studentName" => $student->OriginalStudent->Name,
                    "level" => $documents[0]->course->level,
                    "year" => $year,
                    "role" => (($documents->filter(function ($document) {
                            return !is_null($document->final_second_score);
                        })->count() == 0)?"الاول":"الثاني"),
                    "result" => (($numberOfFailedCourses == 0)?"ناجح":($numberOfFailedCourses <= 2)?"مكمل":"راسب")
                ),
                [],
                $config
            );

        $pdfName = $student->originalStudent->Name."_".Level::get($documents[0]->course->level)."_".$year."_".$season;
        return $pdf->stream($pdfName);
    }

    public function exportDocument($type, $value)
    {
        Auth::check();

        if ($type == "level")
        {

        }

        if ($type == "course")
        {}

        if ($type == "exam")
        {}
    }
}
