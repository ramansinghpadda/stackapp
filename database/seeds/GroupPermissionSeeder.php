<?php

use Illuminate\Database\Seeder;
use App\Permission;
use App\Role;
class GroupPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groupPermissions =[ [
                'name'=>'add-group',
                'display_name'=>'Add Group',
                'description'=>'',
                'roles'=>['admin','owner','manager']
                
            ],
            [
                'name'=>'update-group',
                'display_name'=>'Update Group',
                'description'=>'',
                'roles'=>['admin','owner','manager']
                
            ],
            [
                'name'=>'delete-group',
                'display_name'=>'Delete Group',
                'description'=>'',
                'roles'=>['admin','owner','manager']
                
            ],
            [
                'name'=>'manage-group',
                'display_name'=>'Manage Group',
                'description'=>'',
                'roles'=>['admin','owner','manager']
                
            ]
            ];
        foreach($groupPermissions as $permission){
            $newpermission = Permission::where('name',$permission['name'])->first();
            if(!$newpermission){
                $newpermission = new Permission();
                $newpermission->name         = $permission['name'];
                $newpermission->display_name = $permission['display_name']; // optional
                $newpermission->description  = $permission['description']; // optional
                $newpermission->save();
                $roles = Role::whereIn('name',$permission['roles'])->get();
                
                foreach($roles as $role){
                    $role->attachPermission($newpermission);
                }
                echo 'Permission - '.$permission['name']. ' seeded';
            }
        }
        
    }
}