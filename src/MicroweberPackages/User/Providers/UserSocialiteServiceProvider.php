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

/**
 * Reads social login credentials from the Microweber options table and
 * pushes them into the `social-login` package config array. The package
 * itself is provider-agnostic; this service provider is the CMS-specific
 * glue that wires Microweber's stored settings into the package.
 */
class UserSocialiteServiceProvider extends AuthServiceProvider
{
    /**
     * Map of provider key → [enable_option_key, id_option_key, secret_option_key].
     *
     * @var array<string, array{0: string, 1: string, 2: string}>
     */
    private array $providerMap = [
        'facebook'   => ['enable_user_fb_registration',        'fb_app_id',        'fb_app_secret'],
        'google'     => ['enable_user_google_registration',     'google_app_id',    'google_app_secret'],
        'github'     => ['enable_user_github_registration',     'github_app_id',    'github_app_secret'],
        'twitter'    => ['enable_user_twitter_registration',    'twitter_app_id',   'twitter_app_secret'],
        'linkedin'   => ['enable_user_linkedin_registration',   'linkedin_app_id',  'linkedin_app_secret'],
        'microweber' => ['enable_user_microweber_registration', 'microweber_app_id','microweber_app_secret'],
    ];

    public function boot(): void
    {
        if (!mw_is_installed()) {
            return;
        }

        // Build the callback URL builder using the CMS api_url helper.
        Config::set('social-login.callback_url_builder', function (string $provider): string {
            return api_url('social_login_process?provider=' . $provider);
        });

        foreach ($this->providerMap as $provider => [$enableKey, $idKey, $secretKey]) {
            $enabled = get_option($enableKey, 'users');

            Config::set("social-login.providers.{$provider}.enabled", (bool) $enabled);
            Config::set("social-login.providers.{$provider}.client_id", (string) get_option($idKey, 'users'));
            Config::set("social-login.providers.{$provider}.client_secret", (string) get_option($secretKey, 'users'));

            if ($provider === 'microweber') {
                $serverUrl = get_option('microweber_app_url', 'users');
                if (!empty($serverUrl)) {
                    Config::set("social-login.providers.microweber.server_url", $serverUrl);
                }
            }
        }

        // Refresh the service so it picks up the just-set config values.
        if ($this->app->bound(\MicroweberPackages\SocialLogin\Contracts\SocialLoginServiceContract::class)) {
            /** @var \MicroweberPackages\SocialLogin\Services\SocialLoginService $service */
            $service = $this->app->make(\MicroweberPackages\SocialLogin\Contracts\SocialLoginServiceContract::class);
            $service->refreshConfig();
        }
    }
}
