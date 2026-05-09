<?php

declare(strict_types=1);

namespace Tests\Feature;

use MicroweberPackages\Package\PackageSignatureVerifier;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-124 / AI-131 / SEC-06 — supply-chain signing + Activity
 * Log regression coverage.
 *
 * Pins:
 *   - PackageSignatureVerifier exists at the canonical path with
 *     `verifyArchive` + `isHttpsOnly` static methods.
 *   - HTTPS-only enforcement: rejects http://, ftp://, file://,
 *     etc.; accepts https://.
 *   - Verification round-trip: sign → verify returns true; tamper
 *     → verify returns false.
 *   - activity_log table migration exists with the 3 documented
 *     compound indexes.
 *   - ActivityLogger service exists with the documented API
 *     surface.
 *
 * Style after the cycle-52..123 contract tests.
 */
class Sec06SupplyChainAndActivityLogContractTest extends TestCase
{
    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    #[Test]
    public function signature_verifier_exists_with_canonical_methods(): void
    {
        $src = $this->read('src/MicroweberPackages/Package/PackageSignatureVerifier.php');

        $this->assertStringContainsString(
            'class PackageSignatureVerifier',
            $src,
            'PackageSignatureVerifier class must exist'
        );
        $this->assertStringContainsString(
            'AI-131 / SEC-06',
            $src,
            'PackageSignatureVerifier must carry the AI-131 audit-trail comment'
        );
        $this->assertStringContainsString(
            'public static function verifyArchive',
            $src,
            'must declare verifyArchive(...)'
        );
        $this->assertStringContainsString(
            'public static function isHttpsOnly',
            $src,
            'must declare isHttpsOnly(...)'
        );
    }

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
        // Generate an Ed25519 keypair, sign the digest of a temp
        // file, verify with the verifier — round-trip must succeed.
        if (!function_exists('sodium_crypto_sign_keypair')) {
            $this->markTestSkipped('libsodium not available — round-trip skipped');
        }

        $keypair = sodium_crypto_sign_keypair();
        $secretKey = sodium_crypto_sign_secretkey($keypair);
        $publicKey = sodium_crypto_sign_publickey($keypair);

        $tmp = tempnam(sys_get_temp_dir(), 'mw-pkg-');
        file_put_contents($tmp, "fake archive bytes\n");
        try {
            $digest = hash_file('sha256', $tmp, true);
            $signature = sodium_crypto_sign_detached($digest, $secretKey);
            $sigB64 = base64_encode($signature);

            $this->assertTrue(
                PackageSignatureVerifier::verifyArchive($tmp, $sigB64, $publicKey),
                'Verifier must accept a correctly-signed archive'
            );

            // Tamper: append bytes to the archive — verify must fail.
            file_put_contents($tmp, 'extra', FILE_APPEND);
            $this->assertFalse(
                PackageSignatureVerifier::verifyArchive($tmp, $sigB64, $publicKey),
                'Verifier must reject a tampered archive'
            );
        } finally {
            @unlink($tmp);
        }
    }

    #[Test]
    public function activity_log_migration_exists_with_indexes(): void
    {
        $src = $this->read('database/migrations/2026_05_09_000002_create_activity_log_table.php');

        $this->assertStringContainsString(
            "Schema::create('activity_log'",
            $src,
            'Migration must create activity_log table'
        );

        // Required columns from the brief.
        foreach ([
            'user_id', 'actor_email', 'action', 'subject_type',
            'subject_id', 'ip_address', 'user_agent', 'metadata',
        ] as $col) {
            $this->assertStringContainsString(
                $col,
                $src,
                "activity_log migration must declare column `{$col}`"
            );
        }

        // 3 compound indexes from the doc-comment.
        foreach ([
            'activity_log_action_created_index',
            'activity_log_user_created_index',
            'activity_log_subject_index',
        ] as $idx) {
            $this->assertStringContainsString(
                $idx,
                $src,
                "activity_log migration must declare index `{$idx}`"
            );
        }
    }

    #[Test]
    public function activity_logger_service_exists_with_canonical_api(): void
    {
        $src = $this->read('src/MicroweberPackages/ActivityLog/Services/ActivityLogger.php');

        $this->assertStringContainsString(
            'final class ActivityLogger',
            $src,
            'ActivityLogger must be a final class (no subclassing)'
        );

        foreach ([
            'public static function record(',
            'public static function recordLogin(',
            'public static function recordFailedLogin(',
        ] as $method) {
            $this->assertStringContainsString(
                $method,
                $src,
                "ActivityLogger must expose `{$method}`"
            );
        }

        // Best-effort: every record() call must be wrapped in
        // try/catch so the audit log can never block the action.
        $this->assertMatchesRegularExpression(
            '/try\\s*\\{[\\s\\S]{0,1000}DB::table\\(\'activity_log\'\\)->insert\\([\\s\\S]{0,1000}\\}\\s*catch\\s*\\(\\\\Throwable/',
            $src,
            'ActivityLogger DB write must be wrapped in try/catch (best-effort)'
        );
    }
}
