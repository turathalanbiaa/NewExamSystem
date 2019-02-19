<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
