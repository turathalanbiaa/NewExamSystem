<?php
namespace App\Http\Controllers\Website;
use App\Models\Course;
use App\Models\EduStudent;
use App\Models\Student;
use App\Http\Controllers\Controller;
use App\Models\SystemVariables;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class DocumentController extends Controller
{
    public function index() {
        try {
            $sysVar = SystemVariables::find(1);
            $student = Student::where('remember_token', Cookie::get('remember_me'))->first();
            $documents = \App\Models\StudentDocument::query()
                ->where("student_id", $student->id)
                ->where("season", $sysVar->current_season)
                ->where("year", $sysVar->current_year)
                ->with("course")
                ->get();

            return view("Website/documents")->with([
                "student" => $student,
                "sysVar" => $sysVar,
                "documents" => $documents
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
