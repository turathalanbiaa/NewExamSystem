<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamStudent extends Model
{
    protected $table = "exam_student";
    protected $primaryKey = "id";
    public $timestamps = false;

    protected $fillable = [
        "state"
    ];

    public function student()
    {
        return $this->belongsTo("App\Models\Student", "student_id", "id");
    }

    public function exam()
    {
        return $this->belongsTo("App\Models\Exam", "exam_id", "id");
    }
}
