<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = "student";
    protected $primaryKey = "id";
    public $timestamps = false;

    public function notFinishedExams(){
        return $this->belongsToMany('App\Models\Exam', 'exam_student', 'student_id', 'exam_id')->wherePivot('state',  \App\Enums\ExamStudentState::NOT_FINISHED);
    }

    public function finishedExams(){
        return $this->belongsToMany('App\Models\Exam', 'exam_student', 'student_id', 'exam_id')->wherePivot('state', \App\Enums\ExamStudentState::FINISHED)->withPivot('score');
    }

    public function exams(){
        return $this->belongsToMany('App\Models\Exam', 'exam_student', 'student_id', 'exam_id');
    }

    public function originalStudent()
    {
        return $this->belongsTo("App\Models\EduStudent", "edu_student_id", "ID");
    }

    public function documents()
    {
        return $this->hasMany("App\Models\StudentDocument","student_id", "id");

    }

    public function documentsByLevel($level)
    {
        $sys_vars = SystemVariables::find(1);
        $courses = Course::where("level", $level)->pluck("id")->toArray();
        return $this->hasMany("App\Models\StudentDocument","student_id", "id")
            ->whereIn("course_id", $courses)
            ->where("season", $sys_vars->current_season)
            ->where("year", $sys_vars->current_year)
            ->orderBy("course_id")
            ->get();
    }

    public function documentsForCurrentSeason()
    {
        $sys_vars = SystemVariables::find(1);
        return $this->hasMany("App\Models\StudentDocument","student_id", "id")
            ->where("season", $sys_vars->current_season)
            ->where("year", $sys_vars->current_year)
            ->orderBy("course_id")
            ->get();
    }
}
