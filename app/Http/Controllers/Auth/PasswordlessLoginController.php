<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\UserLoginToken;
use Illuminate\Http\Request;
use App\Auth\PasswordlessAuthentication;
use App\Http\Controllers\Controller;

class PasswordlessLoginController extends Controller
{

	protected $redirectOnRequested = '/signin';

    public function show()
    {
        if (Auth::check()) {
            return redirect('/home')->with('success', 'You\'re already signed in.');
        }
    	return view('auth.signin');
    }

    public function sendToken(Request $request, PasswordlessAuthentication $auth)
    {
    	$this->validateLogin($request);
    	$auth->requestLink();
    	return redirect()->to($this->redirectOnRequested)->with('success', 'We\'ve sent you a magic link');

    }

    public function validateToken(Request $request, UserLoginToken $token)
    {
    	// /$token->delete();
        if ($token->isExpired()) {
    		return redirect()->to($this->redirectOnRequested)->with('error', 'That magic link has expired.');
    	}

    	//if (!$token->belongsToEmail($request->email)) {
    	//	return redirect()->to($this->redirectOnRequested)->with('error', 'Invalid magic link.');
    	//}

    	Auth::login($token->user, $request->remember);

    	return redirect()->intended('home');
    }


    // exists:users,email <- checks that the input email exists 
    // in the users table in te email field. BB 12/02/17
    protected function validateLogin(Request $request)
    {
    	$this->validate($request, [
    		'email' => 'required|email|max:255'
    	]);
    }
}
