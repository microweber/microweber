<?php

namespace MicroweberPackages\Filament\Plugins;

use DutchCodingCompany\FilamentSocialite\FilamentSocialitePlugin;
use DutchCodingCompany\FilamentSocialite\Provider;
use Illuminate\Support\Facades\Config;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;
use Illuminate\Contracts\Auth\Authenticatable;

class MicroweberFilamentSocialitePlugin extends FilamentSocialitePlugin
{

    public string $userClass = \App\Models\User::class;
    public string $socialiteUserClass = \App\Models\User::class;

    public function admin(): MicroweberFilamentSocialitePlugin
    {
       // $this->userClass = Config::get('auth.providers.admins.model', \App\Models\Admin::class);
      //  $this->socialiteUserClass = Config::get('auth.providers.admins.model', \App\Models\Admin::class);
        return $this;
    }

    public function configure(): MicroweberFilamentSocialitePlugin
    {
        $providers = [];


        if (get_option('enable_user_google_registration', 'users')) {

            $providers[] = Provider::make('google')
                ->label('Login with Google')
                ->icon('heroicon-o-user');
        }


        if (get_option('enable_user_fb_registration', 'users')) {
            $providers[] = Provider::make('facebook')
                ->label('Login with Facebook')
                ->icon('heroicon-o-user');
        }

        if (get_option('enable_user_twitter_registration', 'users')) {

            $providers[] = Provider::make('twitter')
                ->label('Login with Twitter')
                ->icon('heroicon-o-user');
        }


        if (get_option('enable_user_github_registration', 'users')) {

            $providers[] = Provider::make('github')
                ->label('Login with Github')
                ->icon('heroicon-o-user');
        }

        if (get_option('enable_user_linkedin_registration', 'users')) {

            $providers[] = Provider::make('linkedin')
                ->label('Login with LinkedIn')
                ->icon('heroicon-o-user');
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
