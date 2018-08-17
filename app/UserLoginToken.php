<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class UserLoginToken extends Model
{

	const TOKEN_EXPIRATION = 300; // 5 Minutes

	protected $table = 'users_login_tokens';
	protected $fillable = [
		'token'
	];

	public function isExpired()
	{
		return $this->created_at->diffInSeconds(Carbon::now()) > self::TOKEN_EXPIRATION;
	}

	public function scopeExpired($query)
	{
		$query->where('created_at', '<', Carbon::now()->subSeconds(self::TOKEN_EXPIRATION));
	}

	public function belongsToEmail($email)
	{
		return (bool) ($this->user->where('email', $email)->count() === 1);
	}

	public function getRouteKeyName()
	{
		return 'token';
	}

   	public function user()
	{
		return $this->belongsTo(User::class);
	}

}
