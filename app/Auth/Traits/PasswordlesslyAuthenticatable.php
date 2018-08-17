<?php

namespace App\Auth\Traits;

use Mail;
use App\UserLoginToken;
use App\Mail\MagicLoginRequested;

trait PasswordlesslyAuthenticatable
{
	public function storeToken()
	{
		// Delete any pre-existing tokens the user may have
		$this->token()->delete();

		$this->token()->create([
			'token' => str_random(255),
		]);

		return $this;
	}

	public function sendMagicLink(array $options)
	{
		Mail::to($this)->send(new MagicLoginRequested($this, $options));
	}

	public function token()
	{

		return $this->hasOne(UserLoginToken::class);
	}
}