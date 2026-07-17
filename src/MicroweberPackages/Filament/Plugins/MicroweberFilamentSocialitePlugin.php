<?php

namespace MicroweberPackages\Filament\Plugins;

use DutchCodingCompany\FilamentSocialite\FilamentSocialitePlugin;
use DutchCodingCompany\FilamentSocialite\Provider;
use MicroweberPackages\SocialLogin\Contracts\SocialLoginServiceContract;

class MicroweberFilamentSocialitePlugin extends FilamentSocialitePlugin
{

    public string $userClass = \App\Models\User::class;
    public string $socialiteUserClass = \App\Models\User::class;

    public function admin(): MicroweberFilamentSocialitePlugin
    {
        return $this;
    }

    public function configure(): MicroweberFilamentSocialitePlugin
    {
        $providers = [];

        /** @var SocialLoginServiceContract $socialLogin */
        $socialLogin = app('social_login');

        $providerLabels = [
            'google'   => 'Login with Google',
            'facebook' => 'Login with Facebook',
            'twitter'  => 'Login with Twitter',
            'github'   => 'Login with Github',
            'linkedin' => 'Login with LinkedIn',
        ];

        foreach ($providerLabels as $name => $label) {
            if ($socialLogin->isProviderEnabled($name)) {
                $providers[] = Provider::make($name)
                    ->label($label)
                    ->icon('heroicon-o-user');
            }
        }

        return MicroweberFilamentSocialitePlugin::make()
            ->providers($providers)
            ->registration(true)
            ->userModelClass($this->userClass)
            ->socialiteUserModelClass($this->socialiteUserClass);
    }

    public function setSocialiteUserClass(string $socialiteUserClass): void
    {
        $this->socialiteUserClass = $socialiteUserClass;
    }

    public function setUserClass(string $userClass): void
    {
        $this->userClass = $userClass;
    }

    public function getUserClass(): string
    {
        return $this->userClass;
    }

    public function getSocialiteUserClass(): string
    {
        return $this->socialiteUserClass;
    }
}
