<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\OrganizationWithDomain;
use App\Plan;
use App\UserPlan;
use App\Role;
use App\Mail\Welcome;
use App\EventLog; 
use Mail;
use App\User;
use Auth;
use App\Collaborator;
use DB;
use SEO;
use App\Organization;
use App\service_catalog as ServiceCatalog;
use App\Scripts\RandomColor;
use App\Application;
use Validator;
use GuzzleHttp\Client as GuzzleHttpClient;


class AnalyzerController extends Controller
{
    //

    public function getIndex(Request $request){
        $url = $request->input('q',null);
        if(!$url){
            return redirect('/');
        }

        SEO::setTitle('Analyze URL');
    
        return view('analyze',compact('url'));
    }

    public function postAnalyze(Request $request){
        
        $validator = Validator::make($request->all(), [
           'url'=>'active_url|url',
        ]);


        if($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }

        $applications = [];
        try{

            $headers = ['X-Api-Key' => env('WAPPALYZER_API_KEY')];
            $client = new GuzzleHttpClient();
            $response = $client->request('GET', 'https://api.wappalyzer.com/lookup/v1/?url='.$request->input('url'),['headers'=>$headers]);
            $json = $response->getBody()->getContents();
            $results = json_decode($json,true);
            foreach($results as $result) {
                if(isset($result['applications'])){
                    foreach($result['applications']  as $application){
                        $applications[] = $application['name'];
                    }
                }
            }
            return response(array_unique($applications));

        }catch(\Exception $e){
            return response(['message'=>$e->getMessage()],400);
        }
        
        
    }

    public function postCreateOrganizationWithDomain(Request $request){
        $data = $request->only(['applications','email','name','password','domain']);
        $isNewUser = false;
        if(!Auth::user()){
            $isNewUser = false;
            $validator = Validator::make($request->all(), [
                'name'=>'required|min:3',
                'email'=>'required|email|unique:users',
                'password' => 'required|confirmed|min:6|max:16',
                'password_confirmation' => 'required',
            ]);


        if($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }

        
        
        /* Creating User */
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'trial_ends_at'=>now()->addDays(1000)
        ]);

        Mail::to($user)->send(new Welcome($user));

        $role = Role::where('name','owner')->first(); /*Sign up user default role*/

        $user->attachRole($role);

        $plan = Plan::where('stripe_plan_id','free')->first();
        
        UserPlan::create([
            'uID'=>$user->id,
            'planID'=>$plan->id,
        ]);

        Auth::login($user);

        }

        $organization = new Organization;
        if ($request->session()->exists('orgID')) {
            $organization =  Organization::find($request->session()->get('orgID'));
            $request->session()->forget('orgID');
        }else{
            $organization->name = $data['domain'];
        }
    
        $organization->uID=Auth::user()->id;
        
        $organization->url = $data['domain'];
        $organization->save();
        if($isNewUser){
            $role = Role::where('name','owner')->first();
            $collaborator = new Collaborator;
            $collaborator->uID = Auth::user()->id;
            $collaborator->oID = $organization->id;
            $collaborator->roleID = $role->id;
            $collaborator->statusID = "1";
            $collaborator->save();
        }

        (new EventLog)->setEventContent([
                    'oID'=>$organization->id,
                    'controller'=> 'Analyzer',
                    'action'=> 'CreateOrganizationWithDomain',
                    'data'=>$organization->toArray()])
                    ->save();
        
        if(isset($data['applications']) && !empty($data['applications'])){
            foreach($data['applications'] as $app){
                
                $hex = RandomColor::one(array('format'=>'hex','luminosity'=>'light','hue'=>array('blue', 'green', 'red')));
                $scRecord = ServiceCatalog::where('name', 'LIKE',$app)->whereNull('is_custom')->first();
                if(!$scRecord){
                        $scRecord  = ServiceCatalog::create(['name'=>$app,
                        'is_custom'=>NULL, 'uID'=>Auth::user()->id,'statusID'=>1,'hex'=>$hex
                    ]);
                    
                }
                
                $application = new Application;
                $application->uID = Auth::user()->id;
                $application->scID = $scRecord->id;
                $application->name = $scRecord->name;
                $application->oID = $organization->id;
                $application->statusID = "1";
                $application->save(); 

            } 
        }

        return response(['success'=>true,'organization'=>$organization]);
    }
}
