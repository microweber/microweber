<?php
namespace Microweber\App;

use Illuminate\Database\Eloquent\Model;

class LoginAttempt extends Model
{
    protected $table = 'login_attempts';
    protected $guarded = ['id'];
    public $timestamps = false;
}