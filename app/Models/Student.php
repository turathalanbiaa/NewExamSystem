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
        return $this->belongsToMany('App\Models\Exam', 'exam_student', 'student_id', 'exam_id')->wherePivot('state', \App\Enums\ExamStudentState::FINISHED);
    }
    public function exams(){
        return $this->belongsToMany('App\Models\Exam', 'exam_student', 'student_id', 'exam_id');
    }
}
