<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Traits\AssertsSkinConsoleClean;
use Tests\TestCase;

/**
 * Plan B.3 fourth-bullet contract — pin the
 * {@see AssertsSkinConsoleClean} trait's noise-filter and
 * channel-aggregation behaviour. The Dusk-facing parts of the
 * trait (installInPageErrorGuard, readInPageErrors, readSevereLogs)
 * need a real browser, but the contract surface that decides
 * "is this entry noise or a real failure" is pure PHP and must
 * be pinned independently — a regression that widens the noise
 * filter would silently weaken every per-skin Dusk test.
 *
 * Five contract slices:
 *
 *   1. Empty channels → assertion passes silently.
 *   2. A real in-page error → assertion fails with the context
 *      label and the event payload in the message.
 *   3. A real SEVERE log entry → assertion fails with the context
 *      label and the entry payload in the message.
 *   4. The exact dev-server chunk-close noise pattern
 *      (`ERR_CONTENT_LENGTH_MISMATCH`) is filtered.
 *   5. The exact fresh-install noise pattern (`install_log.txt`)
 *      is filtered.
 *
 * Lives under tests/Feature/ to inherit the Laravel app boot the
 * sibling trait tests use, even though no DB or HTTP is touched.
 * Composes the trait directly on this TestCase so the protected
 * helpers are reachable without a harness object.
 */
class AssertsSkinConsoleCleanTraitTest extends TestCase
{
    use AssertsSkinConsoleClean;

    #[Test]
    public function empty_channels_pass_quietly(): void
    {
        $this->assertNoConsoleErrorsAgainstChannels('test context', [], []);
    }

    #[Test]
    public function in_page_error_fails_with_context_and_payload_in_message(): void
    {
        $event = [
            'kind' => 'error',
            'message' => 'ReferenceError: foo is not defined',
            'filename' => 'https://example.test/app.js',
            'line' => 42,
        ];

        try {
            $this->assertNoConsoleErrorsAgainstChannels('after insert', [$event], []);
            $this->fail('Expected the trait to throw on a real in-page error');
        } catch (AssertionFailedError $e) {
            $this->assertStringContainsString(
                'after insert',
                $e->getMessage(),
                'Failure message must carry the context label so multiple per-test phases are distinguishable'
            );
            $this->assertStringContainsString(
                'ReferenceError',
                $e->getMessage(),
                'Failure message must include the event payload so the operator can act'
            );
        }
    }

    #[Test]
    public function severe_log_fails_with_context_and_payload_in_message(): void
    {
        $log = [
            'level' => 'SEVERE',
            'message' => 'https://example.test/main.js 12:0 SyntaxError: Unexpected token',
        ];

        try {
            $this->assertNoConsoleErrorsAgainstChannels('after public render', [], [$log]);
            $this->fail('Expected the trait to throw on a real SEVERE browser-log entry');
        } catch (AssertionFailedError $e) {
            $this->assertStringContainsString(
                'after public render',
                $e->getMessage(),
                'Failure message must carry the context label'
            );
            $this->assertStringContainsString(
                'SyntaxError',
                $e->getMessage(),
                'Failure message must include the SEVERE entry payload so the operator can act'
            );
        }
    }

    #[Test]
    public function dev_server_chunk_close_noise_is_filtered_silently(): void
    {
        $this->assertTrue(
            $this->isConsoleNoise('Failed to load resource: net::ERR_CONTENT_LENGTH_MISMATCH'),
            'ERR_CONTENT_LENGTH_MISMATCH (intermittent dev-server chunk-close) must be in the noise filter'
        );
    }

    #[Test]
    public function fresh_install_log_noise_is_filtered_silently(): void
    {
        $this->assertTrue(
            $this->isConsoleNoise('GET https://example.test/install_log.txt 404 (Not Found)'),
            'install_log.txt 404 (only fires on fresh installs) must be in the noise filter'
        );
    }
}
