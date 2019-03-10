<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $table = "assessment";
    protected $primaryKey = "id";
    public $timestamps = false;
}
