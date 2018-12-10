<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $table = "exam";
    protected $primaryKey = "id";
    public $timestamps = false;
}
