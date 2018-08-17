<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Organization;
use App\Application;
use Auth;
use Route;
use App\Plan;
use SEO;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        SEO::setTitle('Home');
        SEO::setDescription('This is your StackrApp dashboard');

        $organizations = Organization::whereHas('collaborators',function($query){
        $query->where('uID',Auth::user()->id)
                ->where('statusID',"1");
        })
        ->where('statusID','=',"1")->paginate(); 
        return view('organizations.index',compact('organizations'));
    }
    


}
