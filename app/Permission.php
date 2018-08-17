<?php

namespace App;

use Laratrust\Models\LaratrustPermission;

class Permission extends LaratrustPermission
{
    //

    public static function getList(){
            return [ [   'name'=>'create-organization',
                'display_name'=>'Create Organization',
                'description' => '',
                'roles'=>['admin','owner']
            ],
            [   'name'=>'view-organization',
                'display_name'=>'View Organizations',
                'description' => '',
                'roles'=>['admin','owner','manager','member']
            ],
            [   'name'=>'update-organization',
                'display_name'=>'Update Organization',
                'description' => '',
                'roles'=>['admin','owner']
            ],
          
            [   'name'=>'delete-organization',
                'display_name'=>'Delete Organization',
                'description' => '',
                'roles'=>['admin','owner']
            ],
            [   'name'=>'manage-organization',
                'display_name'=>'Manage Organization',
                'description' => 'Can change settings of organization',
                'roles'=>['admin','owner']
            ],
            [
                'name'=>'add-application',
                'display_name'=>'Add application to organization',
                'description'=>'',
                'roles'=>['admin','owner','manager']
            ],
            [
                'name'=>'view-application',
                'display_name'=>'Add application to organization',
                'description'=>'',
                'roles'=>['admin','owner','manager','member']
                
            ],
            [
                'name'=>'update-application',
                'display_name'=>'Add application to organization',
                'description'=>'',
                'roles'=>['admin','owner','manager']
                
            ],
            [
                'name'=>'delete-application',
                'display_name'=>'Add application to organization',
                'description'=>'',
                'roles'=>['admin','owner','manager']
                
            ],
            [
                'name'=>'manage-collaborators',
                'display_name'=>'Manage collaborators',
                'description'=>'',
                'roles'=>['admin','owner']
                
            ],
            [
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
        ];
    }
}
