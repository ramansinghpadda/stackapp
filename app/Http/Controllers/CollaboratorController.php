<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Organization; 
use App\Messages;
use App\Collaborator;
use Auth;
use App\Invitation;
use App\Mail\UserInvitation;
use Mail;
use App\EventLog; 
use App\Role;
use SEO;

class CollaboratorController extends AbstractBaseController
{
    //

    public function index(Request $request, $organizationid){
       
       if($organization = Organization::find($organizationid)){

        SEO::setTitle('Team - '. $organization->name);
           
        if(Auth::user()->userrole($organization)->canAccess('manage-collaborators')){
            $collaborators = Collaborator::where('oID',$organization->id)->whereIn('statusID',["0","1"])->get();
            $invitations = Invitation::where('oID',$organizationid)->orderBy('created_at','DESC')->get();
            $roles = Role::whereIn('name',['manager','member'])->get();
            
            return view('collaborators.index',compact('organization','collaborators','invitations','roles'));
        }else{
            return view('error',Messages::notAuthorized());
        }
       }else{
            return view('error',Messages::noRecordFound());
       }
    }

    public function postInvite(Request $request,$organizationid){
       
       $data = $request->only(['email','roleID']);
       $organization=Organization::find($organizationid); 
       if(Auth::user()->userrole($organization) && Auth::user()->userrole($organization)->canAccess('manage-collaborators')){
            if(!Auth::user()->planMemberLimitValid($organization)){
                $request->session()->flash('warning', 'To add more members to your team, please <a href="'.route('user-subscription').'">upgrade</a> your plan.');
                return redirect()->back();
            }
            $invitation = Invitation::where('oID',$organizationid)
                    ->where('email',$data['email'])
                    ->where('statusID',"0")->first();
            if($invitation){
              $request->session()->flash('error', 'The invitation was already sent.');
              return redirect()->back();
            }
            $newinvite = new Invitation;
            $newinvite->oID=$organizationid;
            $newinvite->email = $data['email'];
            $newinvite->code = str_random(128);
            $newinvite->roleID = $data['roleID'];
            $newinvite->statusID = "0";
            $newinvite->email = $data['email'];
            $newinvite->save();
            try{
                 Mail::to($newinvite->email)->send(new UserInvitation($newinvite));
                 (new EventLog)->setEventContent([
                    'oID'=>$organization->id,
                    'controller'=> 'Organization',
                    'action'=> 'addusers',
                    'data'=>$newinvite->toArray()])
                            ->save();
                $request->session()->flash('success', 'The invitation was successfully sent.');
                
            }catch(\Exception $e){
                $request->session()->flash('error', 'Sorry, we are unable to send this invitation.');
            }
            return redirect()->back();
           
            
       }else{
           $request->session()->flash('error', 'You do not have the permissions to manage the team.');
           return redirect('/home');
       }
    }

    public function postResendInvitation(Request $request, $organizationId, $invitationId){
            
            if($invitation = Invitation::find($invitationId)){
                $invitation->code = str_random(128);
                $invitation->save();
                try{
                    Mail::to($invitation->email)->send(new UserInvitation($invitation));
                    (new EventLog)->setEventContent([
                        'oID'=>$invitation->oID,
                        'controller'=> 'Collaborator',
                        'action'=> 'resentInvitation',
                        'data'=>$invitation->toArray()])
                                ->save();
                    $request->session()->flash('success', 'Invitation resent successfully .');
                    
                }catch(\Exception $e){
                    $request->session()->flash('error', 'Sorry, we are unable to send this invitation.');
                }
            }
            return redirect()->back();
    }

    public function postDelete(Request $request,$organizationId,$collaboratorId){
        
       $organization=Organization::find($organizationId); 
       if(Auth::user()->userrole($organization) && Auth::user()->userrole($organization)->canAccess('manage-collaborators')){
            $collaborator = Collaborator::where('id',$collaboratorId)->where('oID',$organization->id)->whereIn('statusID',["1"])->first();
            if($collaborator){
                $collaborator->statusID = "2";
                $collaborator->save();
                $request->session()->flash('success', '<strong>Success!</strong> Permission revoked!');
            }else{
                $request->session()->flash('error', 'No record found');
            }
            
       }else{
            $request->session()->flash('error', 'Sorry, No permission');
       }

       return redirect()->back();
    }


    public function postRoleUpdate(Request $request,$organizationId){
        $organization=Organization::find($organizationId); 
       if(Auth::user()->userrole($organization) && Auth::user()->userrole($organization)->canAccess('manage-collaborators')){
            $data = $request->only(['pk','value','oID']);
            $collaborator = Collaborator::where('id',$data['pk'])->where('oID',$data['oID'])->whereIn('statusID',["1"])->first();
            if($collaborator){
                $collaborator->roleID = $data['value'];
                $collaborator->save();
            }else{
                return response("No collaborator found !",404);
            }

       }else{
          return response("Permission Denied !",403);
       }
    }

    
}
