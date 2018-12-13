<?php

namespace App\Models;

use App\Enums\AdminType;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = "admin";
    protected $primaryKey = "id";
    public $timestamps = false;

    public function getLecturerName() {
        if ($this->type == AdminType::LECTURER) {
            $lecturer = Lecturer::find($this->lecturer_id);
           return $lecturer["name"];
        }

        return "لايوجد";
    }
}
