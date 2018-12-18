<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventLog extends Model
{
    protected $table = "event_log";
    protected $primaryKey = "id";
    public $timestamps = false;

    public static function create($source, $destination, $type, $event)
    {
        $eventLog = new EventLog();
        $eventLog->source = $source;
        $eventLog->destination = $destination;
        $eventLog->type = $type;
        $eventLog->event = $event;
        $eventLog->time = date("Y-m-d h:i:s", time());
        $eventLog->save();
    }
}
