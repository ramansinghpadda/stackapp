<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Organization;
use App\Meta;
use App\MetaMapping;
use App\Http\Requests\MetaAttributeRequest;
use Auth;
use Validator;
use SEO;
class MetaController extends Controller
{
    //
    public function getIndex(Request $request,Organization $organization){

        SEO::setTitle('Columns - '. $organization->name);

        if(!Auth::user()->userrole($organization) || !Auth::user()->userrole($organization)->canAccess('manage-organization')){
           $request->session()->flash('error', 'You do not have the permissions to manage this organization.');
           return redirect("/home");
        }

        $metasOptions = Meta::whereIn('is_custom',['NULL',$organization->id])->orderBy('position','ASC')->pluck('id')->toArray();
        $metaMapping = MetaMapping::whereIn('mID',$metasOptions)->whereIn('statusID', [1,2])->where('oID',$organization->id)->orderBy('position','ASC')->get();
        return view('organizations.meta',compact('organization','metaMapping'));
    }

    public function getMetaform(Request $request,Organization $organization){
        $id = $request->input('id');
        $meta = Meta::find($id);
        return view('organizations._meta-form',compact('organization','meta'));
    }

    public function postStore(Request $request,Organization $organization){

        if(!Auth::user()->userrole($organization) || !Auth::user()->userrole($organization)->canAccess('manage-organization')){
           $request->session()->flash('error', 'You do not have the permissions to manage this organization.');
           return redirect("/home");
        }

        $validator = Validator::make($request->all(), [
           'name' => 'required|max:255',
            'type' => 'required',
            'status'=> 'required',
            'options'=>'required_if:type,option'
        ]);

        if($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }

        try{
        $metasOptionsCount = Meta::whereIn('is_custom',['NULL',$organization->id])->orderBy('position','ASC')->count();
        

        $data = $request->only(['name','type','status','options','id']);
        if(isset($data['id']) && !empty($data['id'])){
            $meta = Meta::find($data['id']);
            $meta->name = $data['name'];
            $meta->type = $data['type'];
            if($data['type'] == 'option'){
                $meta->options = $data['options'];
            }else{
                $meta->options = null;
            }
            
            
            $meta->statusID = $data['status'];
            $meta->save();
            $metaMapping = MetaMapping::where('mID',$meta->id)->first();
            $metaMapping->statusID = $meta->statusID;
            
            $metaMapping->save();
            $request->session()->flash('success', 'Meta attribute updated');
            
        }else{
            $data['statusID'] = $data['status'];
            unset($data['status']);
            $data['is_custom'] = $organization->id;
            $data['uID'] = Auth::user()->id;
            
            $metaAttribute = Meta::create($data);

            $metaAttribute->position = $metasOptionsCount+1; /*Default Position*/
            $metaAttribute->save();
        
            $mapping = MetaMapping::create([
                'statusID'=>$metaAttribute->statusID,
                'oID'=>$organization->id,
                'mID'=>$metaAttribute->id,
            ]);
            $mapping->position = $metasOptionsCount+1; /*Default Position*/
            $mapping->save();

            $request->session()->flash('success', 'Meta attribute added');
        }
        return response()->json(['success'=>true]);
        }catch(\Exception $e){
            return response()->json(['error'=>[$e->getMessage()]]);
        }


    }

    public function deleteMeta(Request $request,Organization $organization,Meta $meta){
        if(!Auth::user()->userrole($organization) || !Auth::user()->userrole($organization)->canAccess('manage-organization')){
           $request->session()->flash('error', 'You do not have the permissions to manage this organization.');
           return redirect("/home");
        }
        
        $meta->statusID = 0; /* Delete Status */
        $meta->save();
        $metaMapping = MetaMapping::where('mID',$meta->id)->where('oID',$organization->id)->first();
        
        if($metaMapping){
            $metaMapping->statusID=$meta->statusID;
            $metaMapping->save();
        }

        $request->session()->flash('success', 'Meta attribute deleted');
        return redirect()->back();
    }

    public function postReposition(Request $request,Organization $organization){
        if(!Auth::user()->userrole($organization) || !Auth::user()->userrole($organization)->canAccess('manage-organization')){
           return response()->json(['error'=>'No Permission to manage organizarion'],403);
        }
        $positions = $request->input('positions',[]);
        $dbTablePositions = $request->input('DBTablePositions',[]);
       
        foreach($positions as $position=>$id){
            $meta = Meta::find($id);
            if($meta){
                $metaMapping = MetaMapping::where('mID',$meta->id)->where('oID',$organization->id)->first();
                if($metaMapping){
                    $metaMapping->position = $position;
                    $metaMapping->save();
                }
            }
        }
        $position = 0 ;
        foreach($dbTablePositions as $id=>$a){
            $meta = Meta::find($id);
            if($meta){
                $metaMapping = MetaMapping::where('mID',$meta->id)->where('oID',$organization->id)->first();
                if($metaMapping){
                    $metaMapping->position = $position;
                    $metaMapping->save();
                    $position++;
                }
            }
        }

        return response()->json(['success'=>true]);
    }
}
