<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Invitation extends Model
{
    
    const INVITATION_EXPIRATION = 48*60*60;

    public function scopeExpired($query)
	{
		$query->where('created_at', '<', Carbon::now()->subSeconds(self::INVITATION_EXPIRATION));
	}

	public function role(){
        return $this->belongsTo('App\Role','roleID');
    }

	public function organization(){
        return $this->belongsTo('App\Organization','oID');
    }

	public function getLink(){
		return url("/invitation/".$this->code);
	}
}
