<?php

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends BaseModel implements AuthenticatableContract, CanResetPasswordContract
{
	use Authenticatable, CanResetPassword;

	protected $hidden = array('password', 'remember_token');

	function setPasswordAttribute($pass) {
		$this->attributes['password'] = Hash::make($pass);
	}

	function getPingAttribute() {
		return 'pong';
	}

	/*function getIfAdminAttribute() {
		return (bool)$this->is_admin;
	}*/
}
