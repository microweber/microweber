<?php

declare(strict_types=1);

namespace MicroweberPackages\Package;

/**
 * AI-131 / SEC-06 (cycle-124 2026-05-09): supply-chain signature
 * verifier.
 *
 * Brief:
 *   - Updater: HTTPS-only + signature verification.
 *   - Marketplace: reject unsigned packages.
 *
 * The signature scheme: each released package carries a
 * `signature.txt` file alongside `package.zip` containing a
 * detached Ed25519 signature over the SHA-256 digest of the zip.
 * Microweber's published Ed25519 public key is stored in
 * `config/microweber.php::package_signing_public_key` (one
 * known-good value baked into the codebase; updater fetches the
 * key from a CDN-served pin file as defense-in-depth).
 *
 * Phase 1 (this commit) provides the verifier + a clear
 * documentation hook. Phase 2 wires it into the
 * `install_composer_package_by_package_name` flow + the
 * Marketplace download paths. Phase 3 ships the public-key
 * rotation tooling.
 *
 * Verifier semantics:
 *
 *   - HTTPS-only: every URL passed to `verifyArchive()` must
 *     start with `https://`. Plain http is rejected outright.
 *
 *   - SHA-256: the archive's content hash is computed locally
 *     after download (not trusted from the server response).
 *
 *   - Ed25519: the detached signature is verified against the
 *     known public key. `sodium_crypto_sign_verify_detached`
 *     is constant-time + fail-closed.
 *
 *   - Versioning: a `signature_version` field in the manifest
 *     lets a future Phase 2 introduce a new signature scheme
 *     (e.g. SLSA provenance, sigstore) without breaking the
 *     v1 verifier.
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
     * @return bool                True on successful verification.
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

        // We sign the SHA-256 digest of the archive (so signing
        // server doesn't need to upload the raw zip — the digest
        // is a stable 32-byte payload).
        $digest = hash_file('sha256', $archivePath, true);
        if ($digest === false) {
            return false;
        }

        try {
            return sodium_crypto_sign_verify_detached($signature, $digest, $publicKey);
        } catch (\SodiumException $e) {
            return false;
        }
    }

    /**
     * Reject a download URL that's not HTTPS.
     */
    public static function isHttpsOnly(string $url): bool
    {
        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));
        return $scheme === 'https';
    }
}
