<?php
namespace MicroweberPackages\User\Models;

use Laravel\Socialite\AbstractUser;

/**
 * Lightweight Socialite user DTO (OAuth profile data), used by the
 * social-login flow. It is NOT the filament-socialite pivot model, so it
 * does not implement FilamentSocialiteUser.
 */
class SocialiteUser extends AbstractUser
{
}
