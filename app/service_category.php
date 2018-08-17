<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class service_category extends Model
{

    protected $fillable = [ 'id', 'name', 'description', 'statusID', 'uID'];


    //Map category using catID
    public function category() {
       return $this->hasOne('App\service_category', 'id', 'catID');
    }

	//Map catalog parent service
    public function parent() {
       return $this->hasOne('App\service_catalog', 'id', 'parentID');
    }

    public function children()
    {
        return $this->hasMany('App\service_category', 'parentID');
    }

    //map user details
    public function user() {
       return $this->hasOne('App\User', 'id', 'uID');
    }
}
