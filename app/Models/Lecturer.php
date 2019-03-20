<?php

namespace App\Models;

use App\Enums\AccountType;
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

    public function events()
    {
        return $this->hasMany("App\Models\EventLog", "account_id","id")
            ->where("account_type", AccountType::LECTURER)
            ->orderBy("id", "DESC")
            ->get();
    }
}
