<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MetaMapping extends Model
{
    //
    protected $table = 'meta_mapping';

    protected $fillable=['mID','statusID','oID'];

    public function meta(){
        return  $this->belongsTo('App\Meta','mID');
    }
}
