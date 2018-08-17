<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Invitation;
use Auth;
use App\Messages;
use App\User;
use App\Collaborator;
use App\EventLog;
use App\Role;
use App\Http\Requests\CreatePassword;
use App\Plan;
use App\UserPlan;

class InvitationController extends Controller
{

    public function index(Request $request,$code){
        if(Auth::user()){
            return redirect('/home');
        }
        if($Invitation = Invitation::where('code',$code)->first()){
            $email = $Invitation->email;
            $user = User::where('email',$Invitation->email)->first();
            if(!$user)
            {
                return view('auth.setup-account',compact('code','email'));
            }

            $collaborator = new Collaborator;
            $collaborator->uID = $user->id;
            $collaborator->oID = $Invitation->oID;
            $collaborator->roleID = $Invitation->roleID;
            $collaborator->statusID = "1";
            $collaborator->save();
            
            (new EventLog)->setEventContent([
                            'data'=>$Invitation->toArray(),
                            'oID'=>$Invitation->oID,
                            'controller'=> 'Invitation',
                            'action'=> 'index',
                            'user_id'=>$user->id])
                            ->save();
            $Invitation->delete();
            return redirect('/login');
        }else{
            return view('error',Messages::noValidCode());
        }
    }
    
    public function postCreatePassword(CreatePassword $request,$code){
        if($Invitation = Invitation::where('code',$code)->first()){
            $user = User::where('email',$Invitation->email)->first();
            if(!$user)
            {
                $user = User::create([ 'name'=>$request->input('name'),'email' => $Invitation->email,'password'=>bcrypt($request->input('password')),'trial_ends_at'=>now()->addDays(1000) ]);
			    $role = Role::where('name','owner')->first();
			    $user->attachRole($role);
                $plan = Plan::where('stripe_plan_id','free')->first();
        
                UserPlan::create([
                    'uID'=>$user->id,
                    'planID'=>$plan->id,
                ]);
            }

            $collaborator = new Collaborator;
            $collaborator->uID = $user->id;
            $collaborator->oID = $Invitation->oID;
            $collaborator->roleID = $Invitation->roleID;
            $collaborator->statusID = "1";
            $collaborator->save();
            
            (new EventLog)->setEventContent([
                            'data'=>$Invitation->toArray(),
                            'oID'=>$Invitation->oID,
                            'controller'=> 'Invitation',
                            'action'=> 'index',
                            'user_id'=>$user->id])
                            ->save();
            $Invitation->delete();
            return redirect('/login');
        }else{
            return view('error',Messages::noValidCode());
        }
    }

}
