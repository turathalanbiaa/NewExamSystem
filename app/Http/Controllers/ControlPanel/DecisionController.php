<?php

namespace App\Http\Controllers\ControlPanel;

use App\Models\Course;
use App\Models\StudentDocument;
use App\Models\SystemVariables;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DecisionController extends Controller
{
    public function index()
    {
        Auth::check();
        return view("ControlPanel.decision.index");
    }

    public function show($level)
    {
        Auth::check();
        $sys_vars = SystemVariables::find(1);
        $courses = Course::where("level", $level)
            ->pluck("id")
            ->toArray();
        $students = StudentDocument::whereIn("course_id", $courses)
            ->where("season", $sys_vars->current_season)
            ->where("year", $sys_vars->current_year)
            ->get()
            ->groupBy("student_id");

        $totalNumberOfStudents = $students->count();
        foreach ($students as $student)
        {
            $scores = $student->sortByDesc("total")->pluck("total")->toArray();
            $count = 0;
            foreach ($scores as $score)
            {
                if ($score < 50)
                    $count++;
            }

            dd($count);
        }
        dd();
        return view("ControlPanel.decision.show")->with([

        ]);
    }

    public function execute($level)
    {

    }
}
