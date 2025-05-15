<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\User\Providers;

use Illuminate\Auth\AuthServiceProvider;
use Illuminate\Support\Facades\Config;
use MicroweberPackages\User\Socialite\MicroweberProvider;


class UserSocialiteServiceProvider extends AuthServiceProvider
{


    public function boot()
    {
        if (mw_is_installed()) {

            if(app()->bound('user_manager') ){

                if (get_option('enable_user_fb_registration', 'users')) {
                    $callback_url = url('oauth/callback/facebook');
                    Config::set('services.facebook.client_id', get_option('fb_app_id', 'users'));
                    Config::set('services.facebook.client_secret', get_option('fb_app_secret', 'users'));
                    Config::set('services.facebook.redirect', $callback_url);
                }

                if (get_option('enable_user_twitter_registration', 'users')) {
                    $callback_url = url('oauth/callback/twitter');
                    Config::set('services.twitter.client_id', get_option('twitter_app_id', 'users'));
                    Config::set('services.twitter.client_secret', get_option('twitter_app_secret', 'users'));
                    Config::set('services.twitter.redirect', $callback_url);
                }

                 if (get_option('enable_user_google_registration', 'users')) {
                    $callback_url = url('oauth/callback/google');
                    Config::set('services.google.client_id', get_option('google_app_id', 'users'));
                    Config::set('services.google.client_secret', get_option('google_app_secret', 'users'));
                    Config::set('services.google.redirect', $callback_url);
                }

                if (get_option('enable_user_github_registration', 'users')) {
                    $callback_url = url('oauth/callback/github');
                    Config::set('services.github.client_id', get_option('github_app_id', 'users'));
                    Config::set('services.github.client_secret', get_option('github_app_secret', 'users'));
                    Config::set('services.github.redirect', $callback_url);
                }

                if (get_option('enable_user_linkedin_registration', 'users')) {
                    Config::set('services.linkedin.client_id', get_option('linkedin_app_id', 'users'));
                    Config::set('services.linkedin.client_secret', get_option('linkedin_app_secret', 'users'));
                    Config::set('services.linkedin.redirect', $callback_url);
                }

                if (get_option('enable_user_microweber_registration', 'users')) {
                    $callback_url  = url('oauth/callback/microweber');
                    $svc = Config::get('services.microweber');
                    if (!isset($svc['client_id'])) {
                        Config::set('services.microweber.client_id', get_option('microweber_app_id', 'users'));
                    }
                    if (!isset($svc['client_secret'])) {
                        Config::set('services.microweber.client_secret', get_option('microweber_app_secret', 'users'));
                    }
                    if (!isset($svc['redirect'])) {
                        Config::set('services.microweber.redirect', $callback_url);
                    }
//                    $socialite = $this->app->make(\Laravel\Socialite\Contracts\Factory::class);
//
//                    $socialite->extend('microweber', function ($app) {
//                        $config = app()->config['services.microweber'];
//
//                        return $this->socialite->buildProvider(MicroweberProvider::class, $config);
//                    });
                }

            }


        }

    }
}
