<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Role;
use App\Permission;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
class SetupPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:access-control';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create application required roles and permissions set';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->confirm('Command will delete Laratrust table, no data will be recovered. Do you wish to continue?')) {
   

        $this->info("Cleaning old LaraTrust DB tables ");
        Schema::dropIfExists('permission_user');
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('organization_users');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('teams');
        

        $this->info("Building new LaraTrust DB tables ");
        
        $this->info("Builing Role table ");
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });
        $this->info("Success! Builded Role table ");
        
        $this->info("Building permissions table ");
        // Create table for storing permissions
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });
        $this->info("Success! Builded permissions table ");

        $this->info("Building role_user table ");
        // Create table for associating roles to users and teams (Many To Many Polymorphic)
        Schema::create('role_user', function (Blueprint $table) {
            $table->unsignedInteger('role_id');
            $table->unsignedInteger('user_id');
            $table->string('user_type');

            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['user_id', 'role_id', 'user_type']);
        });
        $this->info("Success! Builded role_user table ");

        $this->info("Building permission_user table ");
        // Create table for associating permissions to users (Many To Many Polymorphic)
        Schema::create('permission_user', function (Blueprint $table) {
            $table->unsignedInteger('permission_id');
            $table->unsignedInteger('user_id');
            $table->string('user_type');

            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['user_id', 'permission_id', 'user_type']);
        });
        $this->info("Success! Builded permission_user table ");

        $this->info("Building permission_role table ");
        // Create table for associating permissions to roles (Many-to-Many)
        Schema::create('permission_role', function (Blueprint $table) {
            $table->unsignedInteger('permission_id');
            $table->unsignedInteger('role_id');

            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['permission_id', 'role_id']);
        });
        $this->info("Success! Builded permission_role table ");

        Schema::create('teams', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::table('role_user', function (Blueprint $table) {
            // Drop role foreign key and primary key
            $table->dropForeign(['role_id']);
            $table->dropPrimary(['user_id', 'role_id', 'user_type']);

            // Add team_id column
            $table->unsignedInteger('team_id')->nullable();

            // Create foreign keys
            $table->foreign('role_id')->references('id')->on('roles')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('team_id')->references('id')->on('teams')
                ->onUpdate('cascade')->onDelete('cascade');

            // Create a unique key
            $table->unique(['user_id', 'role_id', 'user_type', 'team_id']);
        });

        Schema::table('permission_user', function (Blueprint $table) {
           // Drop permission foreign key and primary key
            $table->dropForeign(['permission_id']);
            $table->dropPrimary(['permission_id', 'user_id', 'user_type']);

            $table->foreign('permission_id')->references('id')->on('permissions')
                ->onUpdate('cascade')->onDelete('cascade');

            // Add team_id column
            $table->unsignedInteger('team_id')->nullable();

            $table->foreign('team_id')->references('id')->on('teams')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->unique(['user_id', 'permission_id', 'user_type', 'team_id']);
        });

        $roles =  Role::getList();

        $this->info("Dumping default roles set \n".json_encode($roles,JSON_PRETTY_PRINT));
        
        foreach($roles as $role){
            $newrole = new Role();
            $newrole->name         = $role['name'];
            $newrole->display_name = $role['display_name']; // optional
            $newrole->description  = $role['description'];// optional
            $newrole->save();
        }
        $this->info("Success! Roles dumped");

        $permissions = Permission::getList();

        $this->info("Dumping default permission set \n".json_encode($permissions,JSON_PRETTY_PRINT));
        foreach($permissions as $permission){
            $newpermission = new Permission();
            $newpermission->name         = $permission['name'];
            $newpermission->display_name = $permission['display_name']; // optional
            $newpermission->description  = $permission['description']; // optional
            $newpermission->save();
            $roles = Role::whereIn('name',$permission['roles'])->get();
            
            foreach($roles as $role){
                $role->attachPermission($newpermission);
            }
        }
        $this->info("Success! Permissions dumped");
        }
    }
}
