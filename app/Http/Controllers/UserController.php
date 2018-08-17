<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Plan;
use Auth;
use DB;
use Stripe\Stripe;
use Stripe\Customer;
use App\EventLog;
use Session;
use Redirect; 


class UserController extends Controller
{
    //

     public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    public function index(Request $request){
        if(Auth::user()->hasRole('superadmin')){
            $request->session()->flash('error', 'No need any subscription as Super Admin');
            return redirect('/home');
        }
        $trialPlan = Auth::user()->onTrial();
        $upgradables = Auth::user()->getUpgradablePlans();
        $isPlanUpgradable = !empty($upgradables) ? true : false;
        $plan = Auth::user()->userplan ? Auth::user()->userplan->plan : null;

        if(!$plan){
            throw new \Exception("No entry found in user plan table");
        }

        $invoices = Auth::user()->getStripeInvoices();
        return view('user.subscription',compact('plan','trialPlan','invoices','isPlanUpgradable'));
         
    }

    public function upgrade(Request $request){

        if(Auth::user()->hasRole('superadmin')){
            $request->session()->flash('error', 'No need any subscription as Super Admin');
            return redirect('/home');
        }
        $cards = Auth::user()->getPaymentSources();
        $plan = Auth::user()->userplan ? Auth::user()->userplan->plan : null;
        if(!$plan){
            throw new \Exception("No entry found in user plan table");
        }


        $plans = Auth::user()->getUpgradablePlans();
        if(empty($plans)){
            $request->session()->flash('error', 'No plan available to upgrade');
            return redirect(route('user-subscription'));
        }
        return view('user.subscription-upgrade',compact('plan','plans','cards'));
    }

    public function update(Request $request){
        $data = $request->only(['stripeToken', 'plan']);
        try{
            if(Auth::user()->subscribed('main')){
                /*If user have subscribed already*/
                Auth::user()->subscription('main')->swap($data['plan']);
                Auth::user()->updatePlan($data['plan']);
                
            }else{
                Auth::user()->newSubscription('main', $data['plan'])->skipTrial()->create($data['stripeToken']);
                Auth::user()->updatePlan($data['plan']);
                
            }
            (new EventLog)->setEventContent([
                    'oID'=>null,
                    'controller'=> 'User',
                    'action'=> 'plan-upgrade',
                    'data'=>Auth::user()->toArray()])
                            ->save();
            $request->session()->flash('success', 'You plan has been upgraded successfully !');
        }catch(\Exception $e){
            $request->session()->flash('error', $e->getMessage());
        }
        
        return redirect(route('user-subscription'));
    }

    public function payment(Request $request){
        if(Auth::user()->hasRole('superadmin')){
            $request->session()->flash('error', 'No need any subscription as Super Admin');
            return redirect('/home');
        }
        return view('user.payment-info');
    }

    public function paymentUpdate(Request $request){
        $token = $request->input('stripeToken');
        if($token){
            try {
                Auth::user()->updateCard($token);
                $request->session()->flash('success', "Payment Information has been update successfully");
                (new EventLog)->setEventContent([
                    'oID'=>null,
                    'controller'=> 'User',
                    'action'=> 'payment-update',
                    'data'=>Auth::user()->toArray()])
                            ->save();
            }catch(\Exception $e){
                $request->session()->flash('error', $e->getMessage());
            }
            return redirect(route('user-subscription'));
        }
        
        $request->session()->flash('error', "No Stripe Payment token found");
        return redirect(route('payment-info'));
    }

        //display the form to edit user profile
    public function edit(Request $request){
        $user = Auth::user(); 
        return view('user/edit', compact('user','edit-profile'));
    }

    //update the user profile changes
     public function save(Request $request){
        $user = Auth::user(); 
        $user->update($request->all());

        (new EventLog)->setEventContent([
                        'oID'=>'',
                        'controller'=> 'Organization',
                        'action'=> 'update',
                        'data'=>$user->toArray()])
                            ->save();

        Session::flash('flash_message', "Profile updated"); 
        return Redirect::back()->with('message','worked');
    }

    public function change(Request $request){
        $user = Auth::user(); 
        return view('user/password', compact('user','user-password-change'));
    }
}
