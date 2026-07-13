<?php

declare(strict_types=1);

namespace MicroweberPackages\Passport\Services;

use phpseclib3\Crypt\RSA;

/**
 * Generates Passport-compatible RSA key pair when the key files are
 * missing from the storage directory. Called on every boot by the
 * service provider so the app never fails with "key not found".
 */
class RSAKeyManager
{
    /**
     * Ensure oauth-public.key and oauth-private.key exist.
     */
    public static function ensureKeys(string $storagePath): void
    {
        $publicKeyPath = $storagePath . '/oauth-public.key';
        $privateKeyPath = $storagePath . '/oauth-private.key';

        if (is_file($publicKeyPath) && is_file($privateKeyPath)) {
            return;
        }

        $privateKey = RSA::createKey(4096);
        $publicKey = $privateKey->getPublicKey();

        $privateKeyValue = $privateKey->toString('PKCS8');
        $publicKeyValue = $publicKey->toString('PKCS8');

        file_put_contents($publicKeyPath, $publicKeyValue);
        file_put_contents($privateKeyPath, $privateKeyValue);
        @chmod($publicKeyPath, 0600);
        @chmod($privateKeyPath, 0600);
    }
}