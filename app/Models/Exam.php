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
        return $this->hasMany("App\Models\Question");
    }

    public function course()
    {
        return $this->belongsTo("App\Models\Course","course_id", "id");
    }

    public function studentEnrolled()
    {
        return $this->hasMany("App\Models\ExamStudent");
    }
}
