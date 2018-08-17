<?php

namespace App\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Role;

class PasswordlessAuthentication
{

	protected $request;
	protected $identifier = 'email';

	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	public function requestLink()
	{
		$user = $this->getUserByIdentifier($this->request->get($this->identifier));

		// Remember checks if the user has checked the remember me box. BB 12/03/17
		$user->storeToken()->sendMagicLink([
			'remember' => $this->request->has('remember'),
			'email' => $user->email,
		]);
	}

	protected function getUserByIdentifier($value)
	{
		$user_exists = User::where($this->identifier, $value)->exists();

		if (!$user_exists) {
			$user = User::create([ 'email' => $value ]);
			$role = Role::where('name','owner')->first();

			$user->attachRole($role);

		}
		return User::where($this->identifier, $value)->firstOrFail();
	}
}