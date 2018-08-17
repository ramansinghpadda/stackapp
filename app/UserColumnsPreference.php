<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserColumnsPreference extends Model
{
    //
    protected $table = 'user_columns_preferences';


    public function getColumnsAttribute($value){
        return !empty($value) ? explode(',',$value) : [];
    }
}


