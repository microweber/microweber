<?php

namespace Microweber\Providers\Socialite;

use Laravel\Socialite\AbstractUser;

class User extends AbstractUser
{
    public $token;

    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }
 

    /**
     * Set the refresh token required to obtain a new access token.
     *
     * @param  string  $refreshToken
     * @return $this
     */
    public function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }

    /**
     * Set the number of seconds the access token is valid for.
     *
     * @param  int  $expiresIn
     * @return $this
     */
    public function setExpiresIn($expiresIn)
    {
        $this->expiresIn = $expiresIn;

        return $this;
    }
}
