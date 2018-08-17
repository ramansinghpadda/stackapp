<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Organization;
use App\Group;
use Validator;
use Auth;
use SEO;

class GroupController extends Controller
{
    //

    public function getIndex(Request $request,$id){
        $organization  = Organization::find($id);

        SEO::setTitle('Groups - '. $organization->name);

        if(!Auth::user()->userrole($organization) || !Auth::user()->userrole($organization)->canAccess('manage-group')){
           $request->session()->flash('error', 'You do not have the permissions to manage group in organization.');
           return redirect("/home");
        }
        $groups = Group::where('oID',$organization->id)->where('statusID',1)->orderBy('name','ASC')->get();
        return view('groups.index',compact('groups','organization'));
    }

    public function getCreate(Request $request,$id){
        $organization  = Organization::find($id);
        if(!Auth::user()->userrole($organization) || !Auth::user()->userrole($organization)->canAccess('add-group')){
           return 'No permission to add group';
        }

        
        $group = Group::find($request->input('id'));
        return view('groups._group-form',compact('organization','group'));
    }

    public function postStore(Request $request,$id){
        $organization  = Organization::find($id);
        if(!Auth::user()->userrole($organization) || !Auth::user()->userrole($organization)->canAccess('add-group')|| !Auth::user()->userrole($organization)->canAccess('update-group')){
           return response()->json(['error'=>['You do not have the permissions to add/update group in organization']]);
        }

        $validator = Validator::make($request->all(), [
           'name' => 'required|max:255'
        ]);

        if($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }
        try{
        
        $data = $request->only(['name','id']);
        if(isset($data['id']) && !empty($data['id'])){
            
            $group = Group::find($data['id']);
            $group->name = $data['name'];
            $group->save();
            $request->session()->flash('success', '<strong>Success !</strong> Group updated');
            
        }else{
            $group = Group::create(['name'=>$data['name'],'uID'=>Auth::user()->id,'oID'=>$id,'statusID'=>1]);
            $request->session()->flash('success', '<strong>Success !</strong> Group created');
        }
        return response()->json(['success'=>true]);
        }catch(\Exception $e){
            return response()->json(['error'=>[$e->getMessage()]]);
        }
    }

    public function postDelete(Request $request,$organizationid,$groupId){
        
        $organization  = Organization::find($organizationid);
        if(!Auth::user()->userrole($organization) || !Auth::user()->userrole($organization)->canAccess('delete-group')){
           $request->session()->flash('error', 'You do not have the permissions to delete group in organization.');
           
           return  redirect()->back();
        }
        $group = Group::find($groupId);
        if($group){
            $group->statusID = 0;
            $group->save();
            $request->session()->flash('success', '<strong>Success !</strong> Group deleted');
            
        }else{
            $request->session()->flash('error', '<strong>Error !</strong> Group not found');
        }
        return  redirect()->back();
    }
}
