<?php

declare(strict_types=1);

namespace MicroweberPackages\SocialLogin\Providers;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\User;

/**
 * Custom Socialite driver for the Microweber OAuth server
 * (mwlogin.com or a self-hosted instance).
 */
class MicroweberSocialiteProvider extends AbstractProvider
{
    /** @var list<string> */
    protected $scopes = [];

    protected string $serverUrl = 'https://mwlogin.com';

    public function setServerUrl(string $url): self
    {
        $this->serverUrl = rtrim($url, '/');

        return $this;
    }

    protected function getAuthUrl($state): string
    {
        return $this->buildAuthUrlFromBase(
            $this->serverUrl . '/oauth/authorize',
            $state
        );
    }

    protected function getTokenUrl(): string
    {
        return $this->serverUrl . '/oauth/token';
    }

    /**
     * @param  string  $code
     * @return array<string, string>
     */
    protected function getTokenFields($code): array
    {
        return [
            'grant_type'    => 'authorization_code',
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code'          => $code,
            'redirect_uri'  => $this->redirectUrl,
        ];
    }

    /**
     * @param  string  $token
     * @return array<string, mixed>
     */
    protected function getUserByToken($token): array
    {
        $response = $this->getHttpClient()->get(
            $this->serverUrl . '/api/user',
            [
                'headers' => [
                    'Accept'        => 'application/json',
                    'Authorization' => 'Bearer ' . $token,
                ],
            ]
        );

        /** @var array<string, mixed> $data */
        $data = json_decode((string) $response->getBody(), true);

        return $data;
    }

    /**
     * @param  array<string, mixed>  $user
     */
    protected function mapUserToObject(array $user): User
    {
        return (new User())->setRaw($user)->map([
            'id'             => $user['id'] ?? null,
            'email'          => $user['email'] ?? null,
            'name'           => $user['name'] ?? null,
            'oauth_uid'      => $user['id'] ?? null,
            'oauth_provider' => 'microweber',
        ]);
    }
}