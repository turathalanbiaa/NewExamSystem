<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RootQuestion extends Model
{
    protected $table = "root_question";
    protected $primaryKey = "id";
    public $timestamps = false;
}
