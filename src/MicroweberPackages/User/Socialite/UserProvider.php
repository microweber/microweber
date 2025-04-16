<?php
namespace MicroweberPackages\User\Socialite;


use MicroweberPackages\User\Models\SocialiteUser;
use MicroweberPackages\User\Models\UserOauthData;

class UserProvider
{
    public static function findOrCreate(\MicroweberPackages\User\Models\SocialiteUser $payload, $provider)
    {
        $user = UserOauthData::where('data_id', '=', $payload->id)->get();
        if ($user->isEmpty()) {
            $data = [
                'email' => $payload->email,
                'username' => $payload->name,
                'password' => md5(time()),
                'is_active' => 1,
            ];
            if (strpos($payload->name, ' ')) {
                $name = explode(' ', $payload->name);
                $data['first_name'] = $name[0];
                $data['last_name'] = $name[1];
            }
            $baseUser = new SocialiteUser($data);
            $baseUser->save();

            app()->cache_manager->delete('users');

            $user = new UserOauthData([
                'user_id' => $baseUser->id,
                'provider' => $provider,
                'data_token' => $payload->token,
                'data_raw' => json_encode($payload->user),
                'data_name' => $payload->name,
                'data_id' => $payload->id,
                'data_email' => $payload->email,
                'data_avatar' => $payload->avatar,
            ]);
            $user->save();
        } else {
            $user = $user->first();
        }

        return SocialiteUser::find($user->user_id);
    }
}
