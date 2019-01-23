<?php

namespace App\Models;

use App\Enums\AccountType;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = "admin";
    protected $primaryKey = "id";
    public $timestamps = false;

    public function getLecturerName() {
        if ($this->type == AccountType::LECTURER) {
            $lecturer = Lecturer::find($this->lecturer_id);
           return $lecturer["name"];
        }

        return "لايوجد";
    }
}
