<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApplicationGroup extends Model
{
    //
    protected $table = 'application_groups';

     protected $fillable=['appID','groupID'];

     public function group(){
         return $this->belongsTo('App\Group','groupID')->select('id','name');
     }
}
