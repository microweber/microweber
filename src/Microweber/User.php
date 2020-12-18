<?php

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

//use Sofa\Revisionable\Laravel\RevisionableTrait;

class User extends BaseModel implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    // use the trait
  //  use RevisionableTrait;

    // Set revisionable whitelist - only changes to any
    // of these fields will be tracked during updates.
    protected $revisionable = [
        'email',
        'username',
        'first_name',
        'last_name',
        'name',
        'last_login',
        'last_login_ip',
        'created_by',
        'edited_by',
        'username',
        'password',
        'email',
        'is_active',
        'is_admin',
        'is_verified',
        'is_public',
        'oauth_uid',
        'oauth_provider',
    ];

    // Or revisionable blacklist - if $revisionable is not set
    // then you can exclude some fields from being tracked.
    protected $nonRevisionable = [
        'created_at',
        'updated_at',
    ];

    //protected $hidden = array('password', 'remember_token');
    protected $fillable = array(
        'updated_at',
        'created_at',
        'expires_on',
        'last_login',
        'last_login_ip',
        'created_by',
        'edited_by',
        'username',
        'password',
        'email',
        'is_active',
        'is_admin',
        'is_verified',
        'is_public',
        'basic_mode',
        'first_name',
        'last_name',
        'thumbnail',
        'parent_id',
        'api_key',
        'user_information',
        'subscr_id',
        'role',
        'medium',
        'oauth_uid',
        'oauth_provider',
        'profile_url',
        'website_url',

    );

    public function setPasswordAttribute($pass)
    {
        $this->attributes['password'] = Hash::make($pass);
    }

    public function getPingAttribute()
    {
        return 'pong';
    }

    /*function getIfAdminAttribute() {
        return (bool)$this->is_admin;
    }*/
}
