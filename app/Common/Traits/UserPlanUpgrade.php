<?php

namespace App\Common\Traits;

use App\Plan;
use App\UserPlan;
use Stripe\Stripe;
use DB;

trait UserPlanUpgrade
{
	public function updatePlan($stripePlanId,$trialExpired = true){
		$plan = Plan::where('stripe_plan_id',$stripePlanId)->first();
		$currentPlan = $this->userplan;
		$currentPlan->planID = $plan->id;
		$currentPlan->save();

		if($trialExpired){
			$this->trial_ends_at = null;
			$this->save();
		}	
	}

	public function getUpgradablePlans(){
		$plan = $this->userplan->plan;
		return  DB::table('plans')->where('price','>',$plan->price)->orderBy('price','asc')->pluck('name', 'stripe_plan_id')->toArray();
	}

	public function getAllPlans(){
		return  DB::table('plans')->orderBy('price','asc')->pluck('name', 'stripe_plan_id')->toArray();
	}
	

}