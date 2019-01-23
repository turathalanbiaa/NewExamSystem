<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = "student";
    protected $primaryKey = "id";
    public $timestamps = false;

    public function exams(){
        return $this->belongsToMany('App\Models\Exam', 'exam_student', 'student_id', 'exam_id');
    }
}
