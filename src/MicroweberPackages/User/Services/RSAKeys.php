<?php

namespace MicroweberPackages\User\Services;

/**
 * Backward-compatibility alias.
 *
 * The real RSA key management has moved to the microweber-passport package.
 *
 * @deprecated Use \MicroweberPackages\Passport\Services\RSAKeyManager
 */
class RSAKeys extends \phpseclib3\Crypt\RSA
{
    public function toString($type, array $options = [])
    {
        // Legacy stub — use RSAKeyManager::ensureKeys() instead.
        return '';
    }
}
