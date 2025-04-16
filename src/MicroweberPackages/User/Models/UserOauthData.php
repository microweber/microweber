<?php namespace MicroweberPackages\User\Models;

use Illuminate\Database\Eloquent\Model;

class UserOauthData extends Model
{
	protected $table = 'users_oauth';
	protected $guarded = ['id'];
	public $timestamps = false;
}
