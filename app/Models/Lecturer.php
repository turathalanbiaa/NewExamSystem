<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
    protected $table = "lecturer";
    protected $primaryKey = "id";
    public $timestamps = false;

    public function courses()
    {
        return $this->hasMany("App\Models\Course");
    }
}
