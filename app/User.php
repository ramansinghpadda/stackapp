<?php

namespace App;

use App\User;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;
use Auth;
use App\Role;
use App\Permission;
use App\Collaborator;
use Laravel\Cashier\Billable;
use Carbon\Carbon;
use App\Common\Traits\UserPlanUpgrade;
use Cache;
use App\Organization;
use DB;


class User extends Authenticatable
{
    use LaratrustUserTrait;
    use Notifiable;
    use Billable;
    use UserPlanUpgrade;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','trial_ends_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function organizations(){
        return $this->hasMany('App\Organization','uID','id');
    }

    public function userplan(){

         return $this->hasOne('App\UserPlan','uID','id');
    }


    public static function canAccess($permission,$organization = null){
        
        if(Auth::user()->hasRole('superadmin')){
            return true;
        }

        if($organization){
            $collaborator = Collaborator::where('oID',$organization->id)->where('statusID','1')->first();
            
            if(!$collaborator){
                return false;
            }
            $role = Role::where('id',$collaborator->roleID)->first();
            $permission = Permission::where('name',$permission)->first();

            if(!$permission){
                return false;
            }

            return $role->permissions->contains($permission);
        }
        
        if(Auth::user()->can($permission)){
            return true;
        } 
        return false;
    }

    public function userrole($organization){
        if(!$organization){
            return false;
        }
        if($this->hasRole('superadmin')){
            return Role::where('name','superadmin')->first();
        }
        $collabrator = Collaborator::where('uID',$this->id)->where('oID',$organization->id)->first();
        return $collabrator && $collabrator->role ? $collabrator->role : null;
    }

    public function getTrialEndsAtAttribute($value){
        return new Carbon($value);
    }


    public function getPaymentSources(){
        if($this->subscribed('main')){
            return Cache::remember('users.'.$this->id.'.cards', 60*24, function() {
                return $this->cards(); /* Data cached for 1 day*/
            }); 
        }
        return [];
    }

    public function getStripeInvoices(){
        if($this->subscribed('main')){
            return Cache::remember('users.'.$this->id.'.invoices',60, function() {
                return $this->invoices(); /* Data cached for 1 hour*/
            }); 
        }
        return [];
    }

   /**
   * Return true all the plans criteria satishfied for organizations.
   */

    public function planOrgLimitValid(){
        if($this->hasRole('superadmin')){
            return true; /*No need to check plan for it*/
        }

        $plan = $this->userplan && $this->userplan->plan ? $this->userplan->plan : null;
        if(!$plan){
            throw new \Exception("No owner plan record found");
        }
        if($plan->num_organizations_limit != '~'){
            $totalOrganizations = Organization::where("uID",$this->id)->where('statusID','=',"1")->count();
            if($totalOrganizations >= $plan->num_organizations_limit){
                return false;
            }
        }
        return true;
    }

    /**
    *@param $organization
    * return  App\User account owner record of $organization
    */
    public function getAccountOwner($organization){

        $collaborator = Collaborator::where('uID',$this->id)->where('oID',$organization->id)->first();
        if(!$collaborator->role->is(['owner'])){
            $role = Role::where('name','owner')->first();
            $collaborator = Collaborator::where('oID',$organization->id)->where('roleID',$role->id)->first();
        }
        if(!$collaborator){
            throw new \Exception("No owner record found");
        }
        return $collaborator && $collaborator->user ? $collaborator->user : null;
    }

    /**
    * Return true all the plans criteria satishfied for application.
    */
    public function planAppLimitValid($organization){
        if($this->hasRole('superadmin')){
            return true;
        }
        
        $accountOwner = $this->getAccountOwner($organization);
    
        $plan  = $accountOwner && $accountOwner->userplan && $accountOwner->userplan->plan ? $accountOwner->userplan->plan : null;
        if(!$plan){
            throw new \Exception("No owner plan record found");
        }
        if($plan->num_applications_limit != "~"){
            $result = DB::select("SELECT count(1) as totalApplications FROM `applications` where oID in (select id from organizations where uID = {$accountOwner->id} AND statusID ='1') AND statusID = '1'");
            if(isset($result[0]) && $result[0]->totalApplications >= $plan->num_applications_limit){
                return false;
            }
        }
        return true;
    }

    /**
    * Return true all the plans criteria satishfied for collabrators.
    */

    public function planMemberLimitValid($organization){
        if($this->hasRole('superadmin')){
            return true;
        }
        $accountOwner = $this->getAccountOwner($organization);
        
        $plan  = $accountOwner && $accountOwner->userplan && $accountOwner->userplan->plan ? $accountOwner->userplan->plan : null;
        if(!$plan){
            throw new \Exception("No owner plan record found");
        }

        if($plan->num_collaborators != "~"){
            $result = DB::select("SELECT count(1) as totalCollaborators FROM `collaborators` where uID NOT IN ($accountOwner->id) AND oID in (select id from organizations where uID = {$accountOwner->id} AND statusID ='1') AND statusID = '1'");
            $totalMembers= $result[0]->totalCollaborators;
           
            $resultInvite= DB::select("SELECT count(1) as totalInvite FROM `invitations` where oID in (select id from organizations where uID = {$accountOwner->id} AND statusID ='1')");
            $totalMembers=$totalMembers+ $resultInvite[0]->totalInvite;

            if($totalMembers >= $plan->num_collaborators){
                return false;
            }
        }
        return true;
    }
}
