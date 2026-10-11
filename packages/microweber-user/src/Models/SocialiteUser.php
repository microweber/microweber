<?php
namespace MicroweberPackages\User\Models;

use DutchCodingCompany\FilamentSocialite\Models\Contracts\FilamentSocialiteUser as FilamentSocialiteUserContract;
use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Socialite\AbstractUser;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;

class SocialiteUser extends AbstractUser  implements FilamentSocialiteUserContract
{

}
