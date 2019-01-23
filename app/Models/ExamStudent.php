<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamStudent extends Model
{
    protected $table = "exam_student";
    protected $primaryKey = "id";
    public $timestamps = false;
}
