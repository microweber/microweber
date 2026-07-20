<?php

namespace MicroweberPackages\SystemLicenses\Tests\Fixtures;

use MicroweberPackages\SystemLicenses\Contracts\LicenseValidatorInterface;

class FakeLicenseValidator implements LicenseValidatorInterface
{
    /** @var string[] License keys that should be treated as valid. */
    protected array $validKeys = [];

    public function setValidKeys(array $keys): void
    {
        $this->validKeys = $keys;
    }

    public function validateRemote(array $licenses): array
    {
        $result = [];

        foreach ($licenses as $license) {
            $relType = $license['rel_type'] ?? 'default';
            $key = $license['local_key'] ?? '';

            if (in_array($key, $this->validKeys, true)) {
                $result[$relType] = ['status' => 'active'];
            } else {
                $result[$relType] = ['status' => 'invalid'];
            }
        }

        return $result;
    }

    public function consumeLicense(string $licenseKey): array
    {
        if (in_array($licenseKey, $this->validKeys, true)) {
            return [
                'valid'   => true,
                'servers' => [
                    [
                        'details' => [
                            'md5hash'        => md5($licenseKey),
                            'registeredname' => 'Test User',
                            'validdomain'    => 'localhost',
                            'status'         => 'active',
                            'productid'      => 1,
                            'serviceid'      => 100,
                            'billingcycle'   => 'monthly',
                            'regdate'        => '2024-01-01 00:00:00',
                            'nextduedate'    => '2025-01-01 00:00:00',
                        ],
                    ],
                ],
            ];
        }

        return ['valid' => false, 'servers' => []];
    }
}