<?php namespace Microweber\App;

use Illuminate\Database\Eloquent\Model;

class UserData extends Model
{
	protected $table = 'users_oauth';
	protected $guarded = ['id'];
	public $timestamps = false;
}
