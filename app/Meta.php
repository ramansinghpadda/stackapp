<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    //

    protected $table = 'meta';

    protected $fillable=['name','type','statusID','is_custom','uID','options'];

    public static $optionsTypes = ["text"=>"Text","integer"=>"Number","date"=>"Date","long_text"=>"Description",'option'=>"Options"]; 


    public function getOptions(){
        return explode(',',$this->options);
    }

    public function metaMapping(){
        return $this->hasMany('App\MetaMapping','mID');
    }
}
