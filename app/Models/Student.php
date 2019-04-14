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
        return $this->hasMany("App\Models\StudentDocument","student_id", "id")
            ->orderBy("year", "ASC")
            ->orderBy("season", "ASC");
    }
}
