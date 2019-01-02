<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = "course";
    protected $primaryKey = "id";
    public $timestamps = false;

    public function lecturer()
    {
        return $this->belongsTo("App\Models\Lecturer","lecturer_id","id");
    }
}
