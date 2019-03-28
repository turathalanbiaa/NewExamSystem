<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentDocument extends Model
{
    protected $table = "student_document";
    protected $primaryKey = "id";
    public $timestamps = false;
    protected $fillable = ["assessment_score"];

    public function course()
    {
        return $this->belongsTo("App\Models\Course", "course_id", "id");
    }
}
