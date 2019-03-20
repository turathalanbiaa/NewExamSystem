<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentDocument extends Model
{
    protected $table = "student_document";
    protected $primaryKey = "id";
    public $timestamps = false;
    protected $fillable = ["student_id", "course_id", "first_month_score", "second_month_score", "final_first_score", "final_second_score", "year"];

    public function course()
    {
        return $this->belongsTo("App\Models\Course", "course_id", "id");
    }
}
