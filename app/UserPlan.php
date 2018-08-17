<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class UserPlan extends Model
{
    protected $table = 'user_plans';
	protected $fillable = [
		'planID',
        'uID'
	];

	public function user(){
		return $this->belongsTo('App\User','uID');
	}

	public function plan(){
		return $this->belongsTo('App\Plan','planID');
	}

	
}


