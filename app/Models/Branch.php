<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;

class Branch extends Model
{
    protected $table = "branch";
    protected $primaryKey = "id";
    public $timestamps = false;

    public function answers()
    {
        return $this->hasMany("App\Models\Answer");
    }

    public function question()
    {
        return $this->belongsTo("App\Models\Question","question_id", "id");
    }

    public function getStudentAnswer()
    {
        $student=Student::where('remember_token',Cookie::get('remember_me'))->first();
        return $this->hasOne("App\Models\Answer")->select('text')->where('student_id',$student->id);
    }

}
