<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
class EventLog extends Model
{
    //
    protected $table = 'event_logs';

    public  function setEventContent($data = []){
        $this->event_data = $data['data'];
        $this->uID = Auth::user() ? Auth::user()->id : $data['user_id'];
        $this->oID = isset($data['oID']) ? $data['oID'] : null;
        $this->controller = isset($data['controller']) ? $data['controller'] : null;
        $this->action = isset($data['action']) ? $data['action'] : null;
        return $this;
    }

    public function setEventDataAttribute($data = [] )
    {
        $this->attributes['event_data'] = is_array($data) ? json_encode($data) : json_encode([]);
    }

    public function getEventDataAttribute()
    {
        return $this->event_data != null ? json_decode($this->event_data,true) : [];
    }

}
