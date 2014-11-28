<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends BaseModel implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	protected $hidden = array('password', 'remember_token');


	public function setPasswordAttribute($pass) {
		$this->attributes['password'] = Hash::make($pass);
	}

	public function getIfAdminAttribute() {

		return (bool)$this->is_admin;
	}

}
