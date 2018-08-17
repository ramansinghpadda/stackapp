<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Role;
use App\User;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'new:superadmin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new super admin';

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
        $name = $this->ask("Enter name");
        $email = $this->ask('Enter is email');
        $password = $this->ask('Enter is password');
        $user= User::where('email',$email)->first();
        if($user == null){
            $user = User::create(['email'=>$email,'password'=>bcrypt($password),'name'=>$name]);
            $role = Role::where('name','superadmin')->first();
            $user->attachRole($role);
            $this->comment("Super admin created!");
        }else{
            $this->error("User already exits with same email");
        }

    }


}
