<?php

namespace MicroweberPackages\SystemLicenses\Contracts;

interface LicenseValidatorInterface
{
    /**
     * Validate a set of licenses against the remote licensing server.
     *
     * @param  array  $licenses  Array of license data arrays, each with at least 'local_key' and optionally 'rel_type'.
     * @return array  Keyed by rel_type, each value containing at least a 'status' field.
     */
    public function validateRemote(array $licenses): array;

    /**
     * Consume (activate) a single license key.
     *
     * @param  string  $licenseKey
     * @return array   Must contain 'valid' (bool) and optionally 'servers' with license details.
     */
    public function consumeLicense(string $licenseKey): array;
}