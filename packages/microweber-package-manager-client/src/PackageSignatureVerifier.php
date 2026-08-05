<?php

declare(strict_types=1);

namespace MicroweberPackages\PackageManagerClient;

/**
 * Supply-chain signature verifier (AI-131 / SEC-06).
 *
 * The signature scheme: each released package carries a detached Ed25519
 * signature over the SHA-256 digest of the zip. Verifier is fail-closed.
 */
final class PackageSignatureVerifier
{
    public const SIGNATURE_VERSION = 1;

    /**
     * Verify a downloaded package archive.
     *
     * @param string $archivePath  Local path to the downloaded archive.
     * @param string $signatureB64 Base64-encoded detached Ed25519 signature.
     * @param string $publicKey    Raw 32-byte Ed25519 public key (NOT base64).
     */
    public static function verifyArchive(string $archivePath, string $signatureB64, string $publicKey): bool
    {
        if (!is_readable($archivePath)) {
            return false;
        }
        if (strlen($publicKey) !== SODIUM_CRYPTO_SIGN_PUBLICKEYBYTES) {
            return false;
        }

        $signature = base64_decode($signatureB64, true);
        if ($signature === false) {
            return false;
        }
        if (strlen($signature) !== SODIUM_CRYPTO_SIGN_BYTES) {
            return false;
        }

        $digest = hash_file('sha256', $archivePath, true);
        if ($digest === false) {
            return false;
        }

        try {
            return sodium_crypto_sign_verify_detached($signature, $digest, $publicKey);
        } catch (\SodiumException) {
            return false;
        }
    }

    /**
     * Reject a download URL that is not HTTPS.
     */
    public static function isHttpsOnly(string $url): bool
    {
        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));

        return $scheme === 'https';
    }
}
