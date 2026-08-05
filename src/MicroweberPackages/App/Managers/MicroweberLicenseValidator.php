<?php

namespace MicroweberPackages\App\Managers;

use MicroweberPackages\PackageManagerClient\Client;
use MicroweberPackages\SystemLicenses\Contracts\LicenseValidatorInterface;

/**
 * Microweber-specific license validator that delegates to the
 * update API and the Composer client for remote validation.
 */
class MicroweberLicenseValidator implements LicenseValidatorInterface
{
    public function validateRemote(array $licenses): array
    {
        $result = app()->update->call('validate_licenses', $licenses);

        return is_array($result) ? $result : [];
    }

    public function consumeLicense(string $licenseKey): array
    {
        $composerClient = new Client();
        $result = $composerClient->consumeLicense($licenseKey);

        return is_array($result) ? $result : ['valid' => false, 'servers' => []];
    }
}