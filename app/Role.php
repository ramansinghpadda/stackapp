<?php

namespace App;

use Laratrust\Models\LaratrustRole;
use Auth;
class Role extends LaratrustRole
{
    //

    public static function getList(){
        return [
            [   'name'=>'superadmin',
                'display_name'=>'Super Admin',
                'description' => 'Super admin can do anything'
            ],
            [   'name'=>'owner',
                'display_name'=>'Owner',
                'description' => 'Owner can do limited resources activities'
            ],
            [   'name'=>'manager',
                'display_name'=>'Manager',
                'description' => 'Manager can do according to access provided by owner'
            ],
            [   'name'=>'member',
                'display_name'=>'Member',
                'description' => 'member has very limited access only able to see not editing allowed'
            ],
        ];
    }

    public function canAccess($permission){

        if(Auth::user()->hasRole('superadmin')){
            return true;
        }
        
        $permission = Permission::where('name',$permission)->first();
        if(!$permission){
            return false;
        }
        return $this->permissions->contains($permission);
    }


    public function is($rolesArray){
        return  in_array($this->name,$rolesArray) ? true : false;
    }

    public static function invitable(){
       return self::whereIn('name',['manager','member'])->get();
    }
}
