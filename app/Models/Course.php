<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = "course";
    protected $primaryKey = "id";
    public $timestamps = false;

    function exams()
    {
        return $this->hasMany('App\Models\Exam', 'course_id');
    }

    public function lecturer()
    {
        return $this->belongsTo("App\Models\Lecturer", "lecturer_id", "id");
    }
}