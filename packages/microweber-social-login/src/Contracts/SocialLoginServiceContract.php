<?php

declare(strict_types=1);

namespace MicroweberPackages\SocialLogin\Contracts;

use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Contract for the social login service.
 *
 * Consumers (like UserManager) depend on this interface rather than the
 * concrete implementation so the service can be swapped or decorated.
 */
interface SocialLoginServiceContract
{
    /**
     * Build the OAuth redirect response for a given provider.
     *
     * @param  string  $provider  Provider key (facebook, google, github, etc.)
     * @return RedirectResponse
     */
    public function redirect(string $provider): RedirectResponse;

    /**
     * Handle the OAuth callback for a given provider and return the
     * authenticated social user DTO.
     *
     * @param  string  $provider
     * @return SocialUserResult
     */
    public function handleCallback(string $provider): SocialUserResult;

    /**
     * Return the list of provider names that are currently enabled
     * (i.e. have both an enabled flag and credentials configured).
     *
     * @return list<string>
     */
    public function enabledProviders(): array;

    /**
     * Check whether a specific provider is enabled and configured.
     */
    public function isProviderEnabled(string $provider): bool;

    /**
     * Get the OAuth callback URL for a provider.
     */
    public function callbackUrl(string $provider): string;
}