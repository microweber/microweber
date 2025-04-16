<?php
namespace MicroweberPackages\User\Socialite;

use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Socialite\AbstractUser;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;
use DutchCodingCompany\FilamentSocialite\Models\Contracts\FilamentSocialiteUser as FilamentSocialiteUserContract;

class SocialiteUser extends AbstractUser  implements FilamentSocialiteUserContract
{
    /**
     * The access token for the user.
     *
     * @var string
     */
    public $accessToken;

    /**
     * The refresh token for the user.
     *
     * @var string
     */
    public $refreshToken;

    /**
     * The number of seconds the access token is valid for.
     *
     * @var int
     */
    public $expiresIn;

    /**
     * The token type.
     *
     * @var string
     */
    public $tokenType;

    /**
     * The raw response from the provider.
     *
     * @var array
     */
    public $raw = [];

    /**
     * The token.
     *
     * @var string
     */

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

    public function getUser(): Authenticatable
    {
        return SocialiteUser::find($this->id);
    }

    public static function findForProvider(string $provider, SocialiteUserContract $oauthUser): ?self
    {
        $oauthData = UserOauthData::where('provider', $provider)
            ->where('data_id', $oauthUser->getId())
            ->first();

        return $oauthData ? SocialiteUser::find($oauthData->user_id) : null;
    }

    public static function createForProvider(string $provider, SocialiteUserContract $oauthUser, Authenticatable $user): FilamentSocialiteUserContract
    {
        $oauthData = new UserOauthData([
            'user_id' => $user->id,
            'provider' => $provider,
            'data_token' => $oauthUser->token ?? null,
            'data_raw' => json_encode($oauthUser->getRaw()),
            'data_name' => $oauthUser->getName(),
            'data_id' => $oauthUser->getId(),
            'data_email' => $oauthUser->getEmail(),
            'data_avatar' => $oauthUser->getAvatar()
        ]);

        $oauthData->save();

        return SocialiteUser::find($user->id);
    }
}
