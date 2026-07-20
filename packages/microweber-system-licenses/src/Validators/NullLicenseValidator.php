<?php

namespace MicroweberPackages\SystemLicenses\Validators;

use MicroweberPackages\SystemLicenses\Contracts\LicenseValidatorInterface;

/**
 * Default no-op validator. Standalone apps should bind their own
 * LicenseValidatorInterface implementation in the container.
 */
class NullLicenseValidator implements LicenseValidatorInterface
{
    public function validateRemote(array $licenses): array
    {
        return [];
    }

    public function consumeLicense(string $licenseKey): array
    {
        return ['valid' => false, 'servers' => []];
    }
}