<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $table = "assessment";
    protected $primaryKey = "id";
    public $timestamps = false;
    protected $fillable = ["student_id", "course_id", "score"];

    public function student()
    {
        return $this->belongsTo("App\Models\Student", "student_id", "id");
    }

    public function course()
    {
        return $this->belongsTo("App\Models\Course", "course_id", "id");
    }
}
