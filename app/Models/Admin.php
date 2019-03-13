<?php

namespace App\Models;

use App\Enums\AccountType;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = "admin";
    protected $primaryKey = "id";
    public $timestamps = false;

    public function events()
    {
        return $this->hasMany("App\Models\EventLog", "account_id","id")
            ->where("account_type", AccountType::MANAGER)
            ->orderBy("id", "DESC")
            ->get();
    }
}
