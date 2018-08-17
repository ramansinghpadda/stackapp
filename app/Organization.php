<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Organization extends Model
{
    
    protected $fillable = ['name', 'description', 'url', 'size', 'industry'];

    public function owner(){
        return $this->belongsTo('App\User','uID');
    }

    public function collaborators(){
        return $this->hasMany('App\Collaborator','oID','id')->where('statusID',2);
    }

    public function applications(){
        return $this->hasMany('App\Application','oID','id')->where('statusID',1);
    }

    public function groups(){
    return $this->hasMany('App\Group','oID','id')->where('statusID',1);
    }

    public function getMetaColumns(){
        return DB::select("SELECT M.id,MP.mID, M.options, M.type, MP.id as mmID,M.name FROM `meta` M INNER JOIN `meta_mapping` MP on MP.mID = M.id where MP.statusID = 1 AND MP.oID={$this->id} order by MP.position ASC");
    }
}
