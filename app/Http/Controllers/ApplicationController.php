<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Organization;
use Auth;
use App\service_catalog as ServiceCatalog;
use App\Application;
use App\Scripts\RandomColor;
use App\EventLog;
use App\MetaMapping;
use App\MetaData;
use DB;
use SEO;
use App\Group;
use App\ApplicationGroup;
use Cache;
use Artisan;
use App\UserColumnsPreference;
use Attachment;
use App\ApplicationAttachment;
use Storage;
use Response;
use Validator;

class ApplicationController extends AbstractBaseController
{
    //
    /**
    @param $id refers organization id
    */

    public function getIndex(Request $request,$id){
       
        $organization  = Organization::find($id);

        $authUser = Auth::user();
        $userRoleInOrganization = $authUser->userrole($organization);

        if(!$userRoleInOrganization || !$userRoleInOrganization->canAccess('view-application')){
            $request->session()->flash('error', 'You do not have the permissions to access this organization.');
            return redirect("/home");
        }
     
        SEO::setTitle($organization->name);

        $groups = Group::where('oID',$id)->where('statusID',1)->orderBy('name','ASC')->get(array('id','name'));
        
        $applications = Application::join('service_catalogs', 'service_catalogs.id', '=', 'applications.scID')
                                ->leftJoin('application_groups','application_groups.appID','=','applications.id')
                                ->where('applications.statusID',1)->where('applications.oID',$id)
                                ->groupBY('id')
                                ->orderBy('name','ASC')
                                ->select(DB::raw("applications.id,applications.oID,service_catalogs.hex,service_catalogs.domain,if(applications.name, applications.name,service_catalogs.name) as name,GROUP_CONCAT(application_groups.groupID) as groupids"))
                                ->get();

        $columnPreferences =  Cache::remember('organization-'.$id.'-'.Auth::user()->id.'-columns', 5*60, function() use ($id) {
            return UserColumnsPreference::where('uID',Auth::user()->id)->where('oID',$id)->first();
        });
        
        return view('applications.index',compact('organization','applications','groups','columnPreferences','authUser','userRoleInOrganization'));
    }

    public function getShow(Request $request,$id,$appId){

       $organization  = Organization::find($id);
       if(!Auth::user()->userrole($organization) || !Auth::user()->userrole($organization)->canAccess('view-application')){
           $request->session()->flash('error', 'You do not have the permissions to access this organization.');
           return redirect("/home");
       }

       $application = Application::where('oID',$id)->where('id',$appId)->where('statusID','1')->first();
       if(!$application){
           $request->session()->flash('error', 'This application is no longer available.');
           return redirect(route('organization-application',$id));
       }
       
       
       $metaAttributes = MetaMapping::where('oID',$id)->where('statusID','1')->orderBy('position','ASC')->get();
       
       return view('applications.view',compact('organization','application','metaAttributes','mappings'));
    }


    public function getCreate(Request $request,$id){
       
       $organization  = Organization::find($id);
       if(!Auth::user()->userrole($organization) || !Auth::user()->userrole($organization)->canAccess('add-application')){
           $request->session()->flash('error', 'Sorry, you do not have have the permissions to add applications.');
           return redirect("/home");
       }

       return view('applications.create',compact('organization'));
    }

    public function postStore(Request $request,$id){

       $organization  = Organization::find($id);
       if(!Auth::user()->userrole($organization) || !Auth::user()->userrole($organization)->canAccess('add-application')){
           if($request->ajax()){
             return  response(['error'=>'No permission to create application'],403);
           }else{
                $request->session()->flash('error', 'Sorry, you do not have have the permissions to add applications.');
                return redirect("/home");
           }
       }
       if(!Auth::user()->planAppLimitValid($organization)){
           if($request->ajax()){
             return  response(['error'=>'Sorry you reached the limit of applications that can be added.'],403);
           }else{
                $request->session()->flash('error', 'You cannot add more applications.');
                return redirect("/home");
           }
       }
       if($request->input('serviceId')){
            $serviceID = $request->input('serviceId');
            //check if application was turned off previously
            $application = Application::where('scID',$serviceID )->where('oID',$organization->id)->where('statusID','0')->first();
            if ($application) {
                $application->statusID = "1";
                $application->save();
            } else {
                $serviceCatalog = ServiceCatalog::find($request->input('serviceId'));

                $application = new Application;
                //$application->name = $serviceCatalog->name;
                $application->uID = Auth::user()->id;
                $application->scID = $serviceCatalog->id;
                $application->oID = $id;
                $application->statusID = "1";
                $application->save(); 

                (new EventLog)->setEventContent([
                            'oID'=>$organization->id,
                            'controller'=> 'Application',
                            'action'=> 'create',
                            'data'=>$application->toArray()])
                            ->save();
            }
            Artisan::call("application:cache",['organizationID'=>$organization->id]);
            return  response(['message'=>'Application added'],200);

       }else if($request->input('name')){

          $hex = RandomColor::one(array('format'=>'hex','luminosity'=>'light','hue'=>array('blue', 'green', 'red')));
               
           $serviceCatalog  = ServiceCatalog::create(['name'=>$request->input('name'),
            'is_custom'=>$id, 'uID'=>Auth::user()->id,'statusID'=>1,'hex'=>$hex
           ]);

            $application = new Application;
            $application->uID = Auth::user()->id;
            $application->scID = $serviceCatalog->id;
            //DA 2018-02-06: do not need this
            //$application->name = $serviceCatalog->name;
            $application->oID = $id;
            $application->statusID = "1";
            $application->save(); 
            (new EventLog)->setEventContent([
                        'oID'=>$organization->id,
                        'controller'=> 'Application',
                        'action'=> 'create',
                        'data'=>$application->toArray()])
                        ->save();
            Artisan::call("application:cache",['organizationID'=>$organization->id]);
            return  response(['message'=>'Application added'],200);
           
       }else{
           return  response(['error'=>'Missing data to create application'],403);
       }
      
    }

    public function getEdit(Request $request,$id,$appId){

        $organization  = Organization::find($id);
       if(!Auth::user()->userrole($organization) || !Auth::user()->userrole($organization)->canAccess('update-application')){
           $request->session()->flash('error', 'You do not have the permissions to access this organization.');
           return redirect("/home");
       }

       $application = Application::where('oID',$id)->where('id',$appId)->where('statusID','1')->first();
       if(!$application){
           $request->session()->flash('error', 'This application is no longer available.');
           return redirect(route('organization-application',$id));
       }
       
       $metaAttributes = MetaMapping::where('oID',$id)->where('statusID','1')->orderBy('position','ASC')->get();
       $groups = Group::where('oID',$id)->where('statusID',1)->orderBy('name','ASC')->get();
       $applicationGroups= ApplicationGroup::where('appID',$appId)->get()->pluck('groupID')->toArray();
       return view('applications.edit',compact('organization','application','metaAttributes','groups','applicationGroups'));
   
    }

    public function postUpdate(Request $request,$id,$appId){
        
        $organization  = Organization::find($id);
        if(!Auth::user()->userrole($organization) || !Auth::user()->userrole($organization)->canAccess('update-application')){
           $request->session()->flash('error', 'Sorry, you do not have have the permissions to add applications.');
           return redirect(route('organization-application',$id));
        }

        $application = Application::where('oID',$id)->where('id',$appId)->where('statusID','1')->first();
        if(!$application){
           $request->session()->flash('error', 'This application is no longer available.');
           return redirect(route('organization-application',$id));
        }

        $data = $request->only(['name','metamapping','groups']);

        if(isset($data['metamapping']) && is_array($data['metamapping'])){
            foreach($data['metamapping'] as $mappingId=>$value){
                    if($value){
                        $meta=MetaData::where('mmID',$mappingId)->where('appID',$appId)->first();
                    if(!$meta){
                        $meta=MetaData::create(['mmID'=>$mappingId,'appID'=>$appId,'value'=>$value]);
                    }else{
                        $meta->value = $value;
                        $meta->save();
                    }
                }
            }
        }
        DB::table('application_groups')->where('appID', $appId)->delete();
        if(isset($data['groups']) && !empty($data['groups'])){
            foreach($data['groups'] as $groupId){
                ApplicationGroup::create(['appID'=>$appId,'groupID'=>$groupId]);
            }
        }
        //update application updated_at
        //DA 2018-02-06: do not need this
        //$application->name = $data['name'];
        $application->updated_at = date('Y-m-d H:i:s');
        $application->save();

        Artisan::call("application:cache",['organizationID'=>$organization->id]);

        $request->session()->flash('success', 'Application "'.$application->servicecatalog->name.'" successfully updated');
        //return redirect(route('organization-application-view',[$organization,$application]));
        return redirect(route('organization-application',$id));
    }

    public function postDelete(Request $request,$id,$appId){

        $organization  = Organization::find($id);
        if(!Auth::user()->userrole($organization) || !Auth::user()->userrole($organization)->canAccess('delete-application')){
           $request->session()->flash('error', 'Sorry, you do not have have the permissions to add applications.');
           return redirect(route('organization-application',$id));
        }

        $application = Application::where('oID',$id)->where('id',$appId)->where('statusID','1')->first();
        if(!$application){
            $request->session()->flash('error', 'This application is no longer available.');
            return redirect(route('organization-application',$id));
        }

        $application->statusID = "0";
        $application->save();
        (new EventLog)->setEventContent([
                        'oID'=>$organization->id,
                        'controller'=> 'Application',
                        'action'=> 'delete',
                        'data'=>$application->toArray()])
                        ->save();
        Artisan::call("application:cache",['organizationID'=>$organization->id]);
        $request->session()->flash('success', 'The application was deleted.');
        return redirect(route('organization-application',$id));

    }

    

    public function postSave(Request $request){
        
        $data = $request->only(['pk','value','name','appID','oID']);
        if((isset($data['name']) && !empty($data['name'])) && (isset($data['pk']) && !empty($data['pk']))){
            if($data['name'] == 'appID'){
                $application = Application::find($data['pk']);
                if($application){
                    //$application->name = $data['value'];
                    $application->save();
                    Cache::forget('organization-'.$application->oID.'-applications');
                }
                return response(["success"=>true]);
            }else if($data['name'] == 'groups'){

               DB::table('application_groups')->where('appID', $data['appID'])->delete();
                if(isset($data['value']) && !empty($data['value'])){
                    foreach($data['value'] as $groupId){
                        ApplicationGroup::create(['appID'=>$data['appID'],'groupID'=>$groupId]);
                    }
                }
                return response(["success"=>true]);
            }
            else if($data['name'] == 'meta'){
                $oID = $data['oID'];
                $appID = $data['appID'];
                $mappingID = $data['pk'];
                
                $mapp=MetaMapping::find($mappingID);
                if($mapp){
                    if($mapp->meta->type =='integer'){
                        if(!is_numeric($data['value'])){
                            return response("Only Digits are allowed: 0-9 ",419);
                        }
                       
                    }
                }
                $metadata = MetaData::where('appID',$appID)->where('mmID',$mappingID)->first();
                if($metadata){
                    $metadata->value = $data['value'];
                    $metadata->save();
                }else{
                    $meta=MetaData::create(['mmID'=>$mappingID,'appID'=>$appID,'value'=>$data['value']]);
                }
                return response(["success"=>true]);
            }
            else{
                return response("Unable to save !",419);
            }
        }else{
            return response("Unable to save",419);
        }
    }

    public function postUpload(Request $request,$orgIDnization,$appID){
        $file = $request->file('file');

        $validator = Validator::make($request->all(), [
            'file' => 'required|max:500000',
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator);
        }

        $application = Application::find($appID);
        $response = $application->upload($file);
        if($response['status']){
            $request->session()->flash('success', $response['message']);
        }else{
            $request->session()->flash('error', $response['message']);
        }
        
        return redirect()->back();
    }

    public function getDocument(Request $request,$oID,$appID,$id){

        $organization  = Organization::find($oID);
        if(!Auth::user()->userrole($organization) || !Auth::user()->userrole($organization)->canAccess('view-application')){
           $request->session()->flash('error','You do not have the permissions to access this organization');
        }

        $attachment = ApplicationAttachment::find($id);

        $url = Storage::disk(config('filesystems.default'))->get('organizations/'.$attachment->oID.'/'.$attachment->appID.'/'.$attachment->file_id );

        $headers = [
            'Content-Type' => "{$attachment->file_type}", 
            'Content-Description' => 'File Transfer',
            'filename'=> $attachment->file_name
        ];

        return response::make($url, 200, $headers);
           
    }

    public function getDownloadDocument(Request $request,$oID,$appID,$id){

        $organization  = Organization::find($oID);
        if(!Auth::user()->userrole($organization) || !Auth::user()->userrole($organization)->canAccess('view-application')){
           $request->session()->flash('error','You do not have the permissions to access this organization');
        }

        $attachment = ApplicationAttachment::find($id);

        $url = Storage::disk(config('filesystems.default'))->get('organizations/'.$attachment->oID.'/'.$attachment->appID.'/'.$attachment->file_id );

        $headers = [
            'Content-Type' => "{$attachment->file_type}", 
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => "attachment; filename={$attachment->file_name}",
            'filename'=> $attachment->file_name
        ];

        return response($url, 200, $headers);
    }

    public function getDeleteDocument(Request $request,$oID,$appID,$id){

        $organization  = Organization::find($oID);
        if(!Auth::user()->userrole($organization) || !Auth::user()->userrole($organization)->canAccess('view-application')){
             $request->session()->flash('error','You do not have the permissions to access this organization');
        }
        try{
        $attachment = ApplicationAttachment::find($id);
        Storage::delete('organizations/'.$attachment->oID.'/'.$attachment->appID.'/'.$attachment->file_id);
        
        $attachment->delete();
        $request->session()->flash('success','File deleted successfully');

        }catch(\Exception $e){
            $request->session()->flash('error',$e->getMessage());
        }
        return redirect()->back();
        
    }

    


    
/*
    //DA 2018-02-06: do not need this
    public function postRestore(Request $request,$id){
        $data = $request->only(['app']);
        $organization  = Organization::find($id);
        $application = Application::where('id',$data['app'])->where('oID',$id)->where('statusID','0')->first();
        if(!Auth::user()->userrole($organization) || !Auth::user()->userrole($organization)->canAccess('update-application')){
            return  response(['error'=>'No permission to restore application'],403);
        }
        if($application){
            $application->statusID = "1";
            $application->save();
            Cache::forget('organization-'.$id.'-applications');
        }else{
            return response(['error'=>"No application found"],404);
        }
    }
*/

}
