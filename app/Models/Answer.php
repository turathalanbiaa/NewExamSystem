<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $table = "answer";
    protected $primaryKey = "id";
    public $timestamps = false;

    protected $fillable = [
        "student_id",
        "branch_id",
        "text",
        "time",
        "score",
        "correction"
    ];

    public function branch()
    {
        return $this->belongsTo("App\Models\Branch", "branch_id", "id");
    }
}
