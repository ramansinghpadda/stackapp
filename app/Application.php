<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\MetaData;
use App\Common\Traits\AttachmentUploadable;
use DB;
use App\Group;
class Application extends Model
{
    use AttachmentUploadable;

    protected $table = 'applications';

    public function servicecatalog(){
        return $this->belongsTo('App\service_catalog','scID')->select('name','domain','hex');
    }

    public function mappedValue($mapping){
        $metaData = MetaData::where('appID',$this->id)->where('mmID',$mapping->id)->select('value')->first();
        if($metaData){
            return $metaData->value;
        }
    }

    public function groups(){
        return $this->hasMany('App\ApplicationGroup','appID','id');
    }

    

    public function getMetaData(){
        return DB::select("SELECT M.id,MP.mID, M.options, M.type, MP.id as mmID,M.name,MD.appID, MD.value as value FROM `meta` M INNER JOIN `meta_mapping` MP on MP.mID = M.id LEFT JOIN meta_data MD on MD.mmID = MP.id AND MD.appID = {$this->id} where MP.statusID = 1 AND MP.oID={$this->oID} order by MP.position ASC");
    }

    public function getAssociatedGroups($groupArray = []){
        if($this->groupids){
            $ids = explode(',',$this->groupids);
            $resultArray=[];
            foreach($ids as $id){
                if(isset($groupArray[$id])){
                    $resultArray[$id] = $groupArray[$id];
                }
            }
            return $resultArray;
        }
        return [];
    }

   
}
