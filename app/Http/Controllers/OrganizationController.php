<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\OrganizationCollection;
use App\Organization;
use App\Http\Requests\OrganizationFormRequest;
use Auth;
use DB;
use App\Collaborator;
use App\User;
use App\Role;
use App\Messages;
use App\Invitation;
use App\Mail\UserInvitation;
use Mail;
use App\EventLog; 
use SEO;
use App\UserColumnsPreference;
use Cache;

class OrganizationController extends AbstractBaseController
{
    private $industry;
    private $company_size;

    public function __construct()
    {
        $this->industry = [
            'accommodations' => 'Accommodations',
            'accounting' => 'Accounting',
            'advertising' => 'Advertising',
            'aerospace' => 'Aerospace',
            'agriculture' => 'Agriculture',
            'air-transportation' => 'Air Transportation',
            'apparel' => 'Apparel',
            'auto' => 'Auto',
            'banking' => 'Banking',
            'beauty' => 'Beauty',
            'biotechnology' => 'Biotechnology',
            'chemical' => 'Chemical',
            'communications' => 'Communications',
            'computer' => 'Computer',
            'construction' => 'Construction',
            'consulting' => 'Consulting',
            'consumer-products' => 'Consumer Products',
            'education' => 'Education',
            'electronics' => 'Electronics',
            'employment' => 'Employment',
            'energy' => 'Energy',
            'entertainment' => 'Entertainment',
            'fashion' => 'Fashion',
            'financial-services' => 'Financial Services',
            'fine-arts' => 'Fine Arts',
            'food-beverage' => 'Food & Beverage',
            'green-technology' => 'Green Technology',
            'health' => 'Health',
            'information' => 'Information',
            'information-technology' => 'Information Technology',
            'insurance' => 'Insurance',
            'journalism-news' => 'Journalism & News',
            'legal-services' => 'Legal Services',
            'manufacturing' => 'Manufacturing',
            'media' => 'Media',
            'medical' => 'Medical',
            'music' => 'Music',
            'pharmaceutical' => 'Pharmaceutical',
            'public-administration' => 'Public Administration',
            'public-relations' => 'Public Relations',
            'publishing' => 'Publishing',
            'real-estate' => 'Real Estate',
            'retail' => 'Retail',
            'service' => 'Service',
            'sports' => 'Sports',
            'technology' => 'Technology',
            'telecommunications' => 'Telecommunications',
            'tourism' => 'Tourism',
            'transportation' => 'Transportation',
            'travel' => 'Travel',
            'utilities' => 'Utilities',
            'video-game' => 'Video Game',
            'web-services' => 'Web Services'
        ];

        $this->company_size = [
            '0-10' => '< 10',
            '11-25' => '11-25',
            '26-50' => '26-50',
            '51-100' => '51-100',
            '101-500' => '101-500',
            '501-1000' => '501-1000',
            '1001-500' => '1001-5000',
            '5001-1000' => '5001-10000',
            '10001' => '10001 +'
        ];
    }

    public function index(Request $request)
    {
        
        SEO::setTitle('Your organizations');
        SEO::setDescription('View all organizations');
        
        $organizations = Organization::whereHas('collaborators',function($query){
        $query->where('uID',Auth::user()->id)
                ->where('statusID',"1");

        })->where('statusID','=',"1")->paginate(); 
        return view('organizations.index',compact('organizations'));
       
    }

    public function show($id)
    {  
        
        $organization  = Organization::find($id); 
        
        //SEO
        SEO::setTitle('Edit - '. $organization->name);
        SEO::setDescription('View all applications for ' .$organization->name);

        if(Auth::user()->userrole($organization)->canAccess('update-organization')){
            return view('organizations.edit',compact('organization'))->with('company_size',$this->company_size)->with('industry',$this->industry);
        }else{
             return view('error',Messages::notAuthorized());
        }
        
    }

    public function create(Request $request){
        //SEO
        SEO::setTitle("Create an organization");
        SEO::setDescription('');
        
        if(!Auth::user()->planOrgLimitValid()){
            $request->session()->flash('error', 'You cannot create more organizations. Please <a class="alert__inline-link" href="'.route('user-subscription').'">upgrade</a> your plan.');
            return redirect('/home');
        }
        $organization = new Organization;
        return view('organizations.create',compact('organization'))->with('company_size',$this->company_size)->with('industry',$this->industry);
    }

    public function store(OrganizationFormRequest $request)
    {
        if(!Auth::user()->planOrgLimitValid()){
            $request->session()->flash('error', 'You cannot create more organizations. Please <a class="alert__inline-link" href="'.route('user-subscription').'">upgrade</a> your plan.');
            return redirect('/home');
        }

        DB::transaction(function() use ($request){
            $organization = new Organization;
            $organization->uID=Auth::user()->id;
            $organization->name = $request->input('name');
            $organization->description = $request->input('description');
            $organization->url = $request->input('url');
            $organization->industry = $request->input('industry');
            $organization->size = $request->input('size');
            $organization->save();
            
            $role = Role::where('name','owner')->first();
            $collaborator = new Collaborator;
            $collaborator->uID = Auth::user()->id;
            $collaborator->oID = $organization->id;
            $collaborator->roleID = $role->id;
            $collaborator->statusID = "1";
            $collaborator->save();

            (new EventLog)->setEventContent([
                        'oID'=>$organization->id,
                        'controller'=> 'Organization',
                        'action'=> 'store',
                        'data'=>$organization->toArray()])
                        ->save();
            $request->session()->put('orgID',$organization->id);
        });
        return redirect('/analyze?q='.urlencode($request->input('url')));
    }

    public function update(OrganizationFormRequest $request, $id)
    {
        $organization=Organization::find($id); 
        if(Auth::user()->userrole($organization) && Auth::user()->userrole($organization)->canAccess('update-organization')){
            $organization->update($request->all());
            (new EventLog)->setEventContent([
                        'oID'=>$organization->id,
                        'controller'=> 'Organization',
                        'action'=> 'update',
                        'data'=>$organization->toArray()])
                            ->save();

            $request->session()->flash('success', '<strong>Success! </strong>Organization updated ');
            return redirect('/organization/' .$id. '/edit');
        }else{
            return view('error',Messages::notAuthorized());
        }
        
    }

    public function delete($id)
    {
        $organization=Organization::find($id); 

        if(Auth::user()->userrole($organization) && Auth::user()->userrole($organization)->canAccess('delete-organization'))
        {
            $organization->statusID = "0"; 
            $organization->update();

            (new EventLog)->setEventContent([
                    'oID'=>$organization->id,
                    'controller'=> 'Organization',
                    'action'=> 'delete',
                    'data'=>$organization->toArray()])
                            ->save();

            return redirect('/home');
        }else{
            return view('error',Messages::notAuthorized());
        }
    
    }

    public function postSaveColumns(Request $request,$id){
        $metaColumns = ($request->input('metaColumns',[]));
        
        $hiddenColumns = array_keys(array_diff($metaColumns,array_filter($metaColumns)));
        
        $columnPreferences = UserColumnsPreference::where('uID',Auth::user()->id)->where('oID',$id)->first();
        if(!$columnPreferences){
            $columnPreferences = new UserColumnsPreference;
            $columnPreferences->oID = $id;
            $columnPreferences->uID = Auth::user()->id;
        }
        $columnPreferences->columns = implode(',',$hiddenColumns);
        $columnPreferences->save();
        Cache::forget('organization-'.$id.'-'.Auth::user()->id.'-columns');
        
    }
}
