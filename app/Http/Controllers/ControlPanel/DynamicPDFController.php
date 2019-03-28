<?php

namespace App\Http\Controllers\ControlPanel;

use App\Enums\Level;
use App\Models\Student;
use App\Models\StudentDocument;
use App\Http\Controllers\Controller;
use PDF;

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

        $pdf = PDF::loadView('ControlPanel.pdf.document', array("documents" => $documents));
        $pdfName = $student->originalStudent->Name."_".Level::get($documents[0]->course->level)."_".$year."_".$season;
        $pdf->stream($pdfName);
    }
}
