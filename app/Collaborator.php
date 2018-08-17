<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Collaborator extends Model
{
    //

    public function role(){
        return $this->belongsTo('App\Role','roleID');
    }

    public function user(){
        return $this->belongsTo('App\User','uID');
    }
}
