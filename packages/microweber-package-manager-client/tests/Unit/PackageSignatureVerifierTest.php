<?php

declare(strict_types=1);

namespace MicroweberPackages\PackageManagerClient\Tests\Unit;

use MicroweberPackages\PackageManagerClient\PackageSignatureVerifier;
use MicroweberPackages\PackageManagerClient\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class PackageSignatureVerifierTest extends TestCase
{
    #[Test]
    public function https_only_enforcement(): void
    {
        $this->assertTrue(PackageSignatureVerifier::isHttpsOnly('https://example.com/pkg.zip'));
        $this->assertFalse(PackageSignatureVerifier::isHttpsOnly('http://example.com/pkg.zip'));
        $this->assertFalse(PackageSignatureVerifier::isHttpsOnly('ftp://example.com/pkg.zip'));
        $this->assertFalse(PackageSignatureVerifier::isHttpsOnly('file:///etc/passwd'));
        $this->assertFalse(PackageSignatureVerifier::isHttpsOnly(''));
    }

    #[Test]
    public function functional_sign_verify_round_trip(): void
    {
        if (!function_exists('sodium_crypto_sign_keypair')) {
            $this->markTestSkipped('libsodium not available');
        }

        $keypair = sodium_crypto_sign_keypair();
        $secretKey = sodium_crypto_sign_secretkey($keypair);
        $publicKey = sodium_crypto_sign_publickey($keypair);

        $tmp = tempnam(sys_get_temp_dir(), 'mw-pkg-');
        $this->assertNotFalse($tmp);
        file_put_contents($tmp, "fake archive bytes\n");

        try {
            $digest = hash_file('sha256', $tmp, true);
            $this->assertNotFalse($digest);
            $signature = sodium_crypto_sign_detached($digest, $secretKey);
            $sigB64 = base64_encode($signature);

            $this->assertTrue(PackageSignatureVerifier::verifyArchive($tmp, $sigB64, $publicKey));

            file_put_contents($tmp, 'extra', FILE_APPEND);
            $this->assertFalse(PackageSignatureVerifier::verifyArchive($tmp, $sigB64, $publicKey));
        } finally {
            @unlink($tmp);
        }
    }
}
