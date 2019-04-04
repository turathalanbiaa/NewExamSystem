<?php

namespace App\Http\Controllers\ControlPanel;

use App\Enums\Level;
use App\Models\Student;
use App\Models\StudentDocument;
use App\Http\Controllers\Controller;
use niklasravnsborg\LaravelPdf\Facades\Pdf;

class DynamicPDFController extends Controller
{
    public function pdf($student, $year, $season)
    {
        Auth::check();
        $student = Student::findOrFail($student);
        $documents = StudentDocument::where("student_id", $student->id)
            ->where("year", $year)
            ->where("season", $season)
            ->get();

//        $mpdf->SetWatermarkImage(public_path("logo.png"));
//        $mpdf->showWatermarkImage = true;

        //Custom config
        $config = [
            'format'      => 'A4',
            'orientation' => 'P',
            'instanceConfigurator' => function($mpdf) use ($student) {
                $mpdf->SetTitle($student->originalStudent->Name);
                $mpdf->SetAuthor("Turath Al-Alanbiaa");
                $mpdf->SetSubject("Certificate");
                $mpdf->SetCreator("Emad Al-Kabi");

                $mpdf->SetWatermarkImage(public_path("document-cover.png"),1.0);
                $mpdf->showWatermarkImage = true;
            }];

        $pdf = PDF::loadView('ControlPanel.pdf.document',
            array("documents" => $documents),
            [],
            $config
        );



        $pdfName = $student->originalStudent->Name."_".Level::get($documents[0]->course->level)."_".$year."_".$season;
        return $pdf->stream($pdfName);
    }
}
