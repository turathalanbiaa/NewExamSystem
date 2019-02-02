<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = "question";
    protected $primaryKey = "id";
    public $timestamps = false;

    public function branches()
    {
        return $this->hasMany("App\Models\Branch");
    }

    public function exam()
    {
        return $this->belongsTo("App\Models\Exam", "exam_id", "id");
    }
}
