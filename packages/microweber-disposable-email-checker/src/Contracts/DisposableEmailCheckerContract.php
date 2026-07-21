<?php

declare(strict_types=1);

namespace MicroweberPackages\DisposableEmailChecker\Contracts;

interface DisposableEmailCheckerContract
{
    /**
     * Check whether the given email address belongs to a disposable / temporary email provider.
     *
     * @param  string  $email  A full email address (user@domain.com).
     * @return bool  True when the domain is disposable, false otherwise.
     */
    public function isDisposable(string $email): bool;

    /**
     * Return the loaded list of blocked domains.
     *
     * @return array<int, string>
     */
    public function blockedDomains(): array;

    /**
     * Add one or more domains to the blocked list at runtime.
     *
     * @param  string|array<int, string>  $domains
     */
    public function addDomains(string|array $domains): void;
}