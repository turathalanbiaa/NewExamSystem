<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $table = "exam";
    protected $primaryKey = "id";
    public $timestamps = false;

    public function questions()
    {
        return $this->hasMany("App\Models\Question")->orderBy("id");
    }

    public function course()
    {
        return $this->belongsTo("App\Models\Course","course_id", "id");
    }

    public function studentsEnrolled()
    {
        return $this->hasMany("App\Models\ExamStudent", "exam_id", "id");
    }
}
