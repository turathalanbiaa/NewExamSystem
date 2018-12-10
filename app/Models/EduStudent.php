<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EduStudent extends Model
{
    protected $connection = 'mysql2';
    protected $table = "student";
    protected $primaryKey = "ID";
    public $timestamps = false;
}
