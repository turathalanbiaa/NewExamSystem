<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventLog extends Model
{
    protected $table = "event_log";
    protected $primaryKey = "id";
    public $timestamps = false;

    public static function create($target, $type, $event)
    {
        $eventLog = new EventLog();
        $eventLog->account_id = session()->get("EXAM_SYSTEM_ACCOUNT_ID");
        $eventLog->account_type = session()->get("EXAM_SYSTEM_ACCOUNT_TYPE");
        $eventLog->target = $target;
        $eventLog->type = $type;
        $eventLog->event = $event;
        $eventLog->time = date("Y-m-d h:i:s", time());
        $eventLog->save();
    }
}
