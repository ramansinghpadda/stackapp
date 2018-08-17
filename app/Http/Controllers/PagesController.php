<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use SEO;
use Mail;
use Auth;
use App\User;

class PagesController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function pricing(Request $request)
    {
        
        SEO::setTitle('Pricing');
        SEO::setDescription('See all the pricing plans for StackrApp');
    	$page = "pricing";
        $plans = DB::table('plans')->get();
    	return view('pages', ['plans' => $plans, 'page' => $page]);
    }

    public function contact(Request $request)
    {
        
        SEO::setTitle('Contact us');
        SEO::setDescription('Send the StackrApp team a message.');
    	$page = "contact";
        return view('pages',compact('page'));
    }

        public function features(Request $request)
    {
        
        SEO::setTitle('Features');
        SEO::setDescription('view all the features StackrApp offers.');
    	$page = "features";
        return view('pages',compact('page'));
    }

        public function about(Request $request)
    {
        
        SEO::setTitle('About');
        SEO::setDescription('Leanr more about StackrApp and its mission.');
    	$page = "about";
        return view('pages',compact('page'));
    }

}
