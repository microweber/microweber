<?php

declare(strict_types=1);

namespace MicroweberPackages\DisposableEmailChecker\Services;

use MicroweberPackages\DisposableEmailChecker\Contracts\DisposableEmailCheckerContract;

class DisposableEmailChecker implements DisposableEmailCheckerContract
{
    /**
     * @var array<int, string>  Normalised (lower-case, trimmed) blocked domains.
     */
    private array $domains = [];

    public function __construct()
    {
        $this->loadDefaults();
    }

    /**
     * Check whether the given email address belongs to a disposable / temporary email provider.
     */
    public function isDisposable(string $email): bool
    {
        if ($this->domains === []) {
            return false;
        }

        $parts = explode('@', $email);

        if (!isset($parts[1])) {
            return false;
        }

        $domain = strtolower(trim($parts[1]));

        return in_array($domain, $this->domains, true);
    }

    /**
     * Return the loaded list of blocked domains.
     *
     * @return array<int, string>
     */
    public function blockedDomains(): array
    {
        return $this->domains;
    }

    /**
     * Add one or more domains to the blocked list at runtime.
     *
     * @param  string|array<int, string>  $domains
     */
    public function addDomains(string|array $domains): void
    {
        $domains = is_string($domains) ? [$domains] : $domains;

        foreach ($domains as $domain) {
            $normalised = strtolower(trim($domain));

            if ($normalised !== '' && !in_array($normalised, $this->domains, true)) {
                $this->domains[] = $normalised;
            }
        }
    }

    /**
     * Load the default domain list shipped with the package.
     */
    private function loadDefaults(): void
    {
        $listPath = config(
            'disposable-email-checker.list_path',
            __DIR__ . '/../../resources/data/disposable_email_addresses.txt'
        );

        if (!is_file($listPath)) {
            return;
        }

        $contents = file_get_contents($listPath);

        if ($contents === false) {
            return;
        }

        $lines = explode("\n", $contents);

        foreach ($lines as $line) {
            $domain = strtolower(trim($line));

            if ($domain !== '') {
                $this->domains[] = $domain;
            }
        }
    }
}